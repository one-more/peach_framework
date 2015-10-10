<?php

class SilentSiteRouter extends \Starter\router\SiteRouter {

    public function __destruct() {}

    protected function show_result(AjaxResponse $response) {}
}

\Starter::$current_router = \Starter\router\SiteRouter::class;

/**
 * Class SiteRouterTest
 *
 * @method bool assertNull($var)
 */
class SiteRouterTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $router \Starter\router\SiteRouter
     */
    private $router;

    public function setUp() {
        $this->router = new SilentSiteRouter();
    }

    /**
     * @covers \Starter\router\SiteRouter::__construct
     */
    public function __construct() {
        new SilentSiteRouter();
    }

    /**
     * @covers \Starter\router\SiteRouter::action_edit_user
     */
    public function test_action_edit_user() {
        /**
         * @var $user \User
         */
        $user = Application::get_class('User');
        $mapper = $user->get_mapper();
        $sql = 'SELECT * FROM users WHERE deleted = 0 ORDER BY id DESC LIMIT 1';
        /**
         * @var $model \User\model\UserModel
         */
        $model = $mapper->find_by_sql($sql)->one();
        $_COOKIE['user'] = $model->remember_hash;
        $_POST['id'] = $model->get_id();
        $_POST['login'] = uniqid('test', true);
        $this->router->action_edit_user();

        $_POST['id'] = $model->get_id() + 100;
        $this->router->action_edit_user();

        $this->assertNull(error_get_last());
    }
}
