<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\UsersSearch;
use app\models\ChangePasswordForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Users models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->identity->isAdmin()) {
            throw new \yii\web\ForbiddenHttpException('Accès réservé à l\'admin.');
        }

        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate()
    {
        $model = new Users();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if(!empty($model->password)){
                    $model->password = Users::hashPassword($model->password);
                } else {
                    throw new \yii\web\ForbiddenHttpException('Mot de passe requis');
                }
                $model->auth_key = Yii::$app->security->generateRandomString();
                $model->created_at = date('Y-m-d H:i:s');
                $model->setTypeUserId(2);
                $model->isMailSend = true;

                if ($model->save()) {
                    if (Yii::$app->user->isGuest) {
                        return $this->redirect(['site/login']);
                    } else {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {

            // $model->password = Users::hashPassword($model->password);
            $model->password = $model->getOldAttribute('password');

            $model->updated_at = date('Y-m-d H:i:s');
            
            if ($model->save()) {
                if (Yii::$app->user->identity->isAdmin()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    return $this->redirect(['profil']);
                }
            }
        }

        var_dump($this->request->post());
        var_dump($model->errors); // Affiche les erreurs de validation

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionChangePassword()
    {
        $id = Yii::$app->user->identity->id;
        $user = $this->findModel($id);
        $model = new ChangePasswordForm();
    
        if ($model->load($this->request->post()) && $model->validate()) {
            $newPassword = $model->changePassword($user->id);
            $newPassword = Users::hashPassword($newPassword);
    
            Yii::$app->session->setFlash('success', 'Le mot de passe a été modifié avec succès.');
    
            if (Yii::$app->user->identity->isAdmin()) {
                return $this->redirect(['view', 'id' => Yii::$app->user->id]);
            } else {
                return $this->redirect(['profil']);
            }
        }
    
        return $this->render('change-password', [
            'model' => $model,
        ]);
    }
    


    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->isAdmin()) {
            throw new \yii\web\ForbiddenHttpException('Vous ne pouvez pas supprimer un compte administrateur.');
        }

        $model->delete();

        if (Yii::$app->user->identity->isAdmin()) {
            return $this->redirect(['index']);
        } else {
            return $this->redirect(Yii::$app->urlManager->createUrl(['site/login']));
        }
    }

    public function actionProfil()
    {
        $id = Yii::$app->user->getId();
        $model = $this->findModel($id);

        return $this->render('profil', [
            'model' => $model,
        ]);
    }

    public function actionPolitic()
    {
        return $this->render('politic');
    }


    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
