<?php

namespace app\controllers;

use Yii;
use app\models\Item;
use app\models\ItemSearch;
use app\models\Users;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
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
     * Lists all Item models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Item model.
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
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Item();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }      

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGet(){
        $user = Yii::$app->user->identity;
        $itemId = $user->id_item;

        $item = Item::findOne($itemId);

        return $this->render('get', [
            'item' => $item,
        ]);
    }

    // public function actionChange()
    // {
    //     // $user = Yii::$app->user->identity;
    //     // $currentItemId = $user->id_item;

    //     // $newItemId = ($currentItemId == 1) ? 2 : 1;

    //     // Mettre à jour l'id_item de l'utilisateur
    //     // $user->id_item = $newItemId;
    //     // $user->setItemId($newItemId);
    //     // $user->save();

    //     $id_user = Yii::$app->user->identity->id;
    //     $u = new Users();
    //     $u->findOne($id_user);

    //     $currentItemId = $u->getItemId();

    //     $newItemId = ($currentItemId == 1) ? 2 : 1;

    //     $u->setItemId($newItemId);
    //     $u->save();

    //     // $item = $user->getItem()->one();
    //     $item = $u->getItem()->one();

    //     return $this->render('get', [
    //         'item' => $item,
    //     ]);

    //     // return $this->redirect(['site/index']);
    // }

    public function actionChange()
    {
        $user = Yii::$app->user->identity;
        $currentItemId = $user->id_item;

        $newItemId = ($currentItemId == 1) ? 2 : 1;

        $user->setItemId($newItemId);
        $user->save();

        $item = $user->getItem()->one();

        if (!$user->save()) {
            var_dump($user->errors); // Affiche les erreurs de validation
        }        

        return $this->render('get', [
            'item' => $item,
        ]);
    }
}
