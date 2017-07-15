<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     * главная страница
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     * страница логина
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     * страница выхода
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     * страница контактов
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     * страница about
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionHello($message = "Hello World"){
        return $this->render('hello');
        //передаем message
        ['message' => $message];

    }

    public function actionSay($message = 'Привет'){
        return $this->render('say', ['message' => $message]);
    }

    /**
     * work to form
     */
    public function actionEntry()
    {
        $model = new EntryForm();//создает объект
        /**Затем оно пытается заполнить мо дель данными из массива $_POST, доступ к которому обеспечивает Yii при помощи yii\web\Request : : post ( ) . Если модель успешно заполнена, тоесть пользователь отправил данные из HTML формы, то для проверки данных будет вызван метод validate ( ) .*/
        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            // данные в $model удачно проверены
            // делаем что-то полезное с $model
            return $this->render('entry-confirm', ['model' => $model]);//Если всј в порядке, действие отобразит представление entry-confirm,
        }else{
            // либо страница отображается первый раз , либо есть ошибка в данных
            return $this->render('entry', ['model' => $model]);
            /**Информация: Yii : : $app представляет собой глобально доступный экземпляр-одиночку приложения (singleton) . Одновременно это Service Locator,дающий доступ к компонентам вро де request , response, db и так далее. В коде выше для доступа к данным из $_POST был использован компонент request.*/
        }
    }

}
