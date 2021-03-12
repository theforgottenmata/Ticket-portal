<?php



    namespace App;

    use Nette\Application\IRouter;
    use Nette\Application\Routers\Route;
    use Nette\Application\Routers\RouteList;
    use Nette\StaticClass;

    /**
     * Továrna na routovací pravidla.
     * Řídí směrování a generovaní URL adres v celé aplikaci.
     * @package App
     */
    class RouterFactory
    {
        use StaticClass;

        /**
         * Vytváří a vrací seznam routovacích pravidel pro aplikaci.
         * @return IRouter výsledný router pro aplikaci
         */
        public static function createRouter()
        {
            $router = new RouteList;


            $router[] = new Route('logout', 'Backend:Administration:logout');
            $router[] = new Route('administrace/Seznam', 'Backend:Article:list');
            $router[] = new Route('administrace/Editor/<name>', 'Backend:Article:edit');
            $router[] = new Route('administrace/<name>', 'Backend:Article:default');
            $router[] = new Route('administrace', 'Backend:Administration:default');
            $router[] = new Route('registrace', 'Backend:Administration:register');
            $router[] = new Route('login', 'Backend:Administration:login');
            $router[] = new Route('objednavky', 'Backend:Order:list');








            $router[] = new Route('chyba', 'Homepage:error');

            $router[] = new Route('kontakt', 'Contact:default');

            $router[] = new Route('objednavka/<name>', 'Order:default');

            $router[] = new Route('seznam-akce/<name>', 'Action:default');
            $router[] = new Route('seznam-akci', 'Action:list');










            $router[] = new Route('<presenter>/<action>', 'Homepage:default');
            return $router;
        }
    }
