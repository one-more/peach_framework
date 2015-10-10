<?php

/**
 * Class SilentRouter
 *
 * @decorate AnnotationsDecorator
 */
class SilentRouter {
    use \Starter\router\TraitStarterRouter;

    public function __construct() {
        $this->response = new JsonResponse();
    }

    public function __destruct() {}

    protected function show_result(AjaxResponse $response) {}
}

/**
 * Class TraitStarterRouterTest
 *
 * @method assertNull($var)
 */
class TraitStarterRouterTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $router SilentRouter
     */
    private $router;

    public function setUp() {
        $this->router = Application::get_class('SilentRouter');

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        }
    }

    /**
     * @covers TraitStarterRouter::action_login
     */
    public function test_login() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPRequest';
        \Starter::$current_router = \Starter\router\SiteRouter::class;

        $this->router->action_login();

        /**
         * @var $user User
         */
        $user = Application::get_class('User');
        $mapper = $user->get_mapper();
        $sql = 'SELECT * FROM users WHERE deleted = 0 ORDER BY id DESC LIMIT 1';
        /**
         * @var $model \User\model\UserModel
         */
        $model = $mapper->find_by_sql($sql)->one();
        $_POST['login'] = $model->login;
        $_POST['password'] = $model->password;
        $this->router->action_login();

        \Starter::$current_router = \Starter\router\AdminPanelRouter::class;
        $this->router->action_login();

        $model = $mapper->find_where([
            'password' => ['!=', '']
        ])->one();
        $_POST['login'] = $model->login;
        $_POST['password'] = $model->password;
        $this->router->action_login();

        $this->assertNull(error_get_last());
    }

    /**
     * @covers TraitStarterRouter::action_login
     * @expectedException WrongRequestMethodException
     */
    public function test_login_wrong_request() {
        $this->router->action_login();
    }

    /**
     * @covers TraitStarterRouter::action_logout
     */
    public function test_logout() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPRequest';

        $this->router->action_logout();

        $this->assertNull(error_get_last());
    }

    /**
     * @covers TraitStarterRouter::action_logout
     * @expectedException WrongRequestMethodException
     */
    public function test_logout_wrong_request() {
        $this->router->action_logout();
    }

    /**
     * @covers TraitStarterRouter::action_edit_user
     */
    public function test_edit_user() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPRequest';

        $this->router->action_edit_user();

        /**
         * @var $user User
         */
        $user = Application::get_class('User');
        $mapper = $user->get_mapper();
        $sql = 'SELECT * FROM users WHERE deleted = 0 ORDER BY id DESC LIMIT 1';
        /**
         * @var $model \User\model\UserModel
         */
        $model = $mapper->find_by_sql($sql)->one();
        $_POST['id'] = $model->id;
        $_POST['login'] = uniqid('test', true);
        $this->router->action_edit_user();

        $this->assertNull(error_get_last());
    }

    /**
     * @covers TraitStarterRouter::action_edit_user
     * @expectedException WrongRequestMethodException
     */
    public function test_edit_user_wrong_request() {
        $this->router->action_edit_user();
    }

    /**
     * @covers TraitStarterRouter::action_add_user
     */
    public function test_add_user() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPRequest';

        $this->router->action_add_user();

        $_POST['login'] = uniqid('test', true);
        $this->router->action_add_user();

        $this->assertNull(error_get_last());
    }

    /**
     * @covers TraitStarterRouter::action_add_user
     * @expectedException WrongRequestMethodException
     */
    public function test_add_user_wrong_request() {
        $this->router->action_add_user();
    }
}
