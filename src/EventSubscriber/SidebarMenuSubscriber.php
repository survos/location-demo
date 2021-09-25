<?php // generated by @SurvosBase/SidebarMenuSubscriber.php.twig

namespace App\EventSubscriber;

use Knp\Menu\ItemInterface;
use Survos\BaseBundle\Menu\BaseMenuSubscriber;
use Survos\BaseBundle\Menu\MenuBuilder;
use Survos\BaseBundle\Traits\KnpMenuHelperTrait;
use Survos\BaseBundle\Event\KnpMenuEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class SidebarMenuSubscriber extends BaseMenuSubscriber implements EventSubscriberInterface
{
    use KnpMenuHelperTrait;

    private AuthorizationCheckerInterface $security;

    public function __construct(AuthorizationCheckerInterface $security)
    {
        $this->security = $security;
    }

    public function onKnpMenuEvent(KnpMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $this->addMenuItem($menu, ['route' => 'app_homepage']);
        $this->addMenuItem($menu, ['route' => 'article_index']);
        $this->addMenuItem($menu, ['route' => 'article_new']);
        $this->addMenuItem($menu, ['route' => 'app_html_tree']);
        $this->addMenuItem($menu, ['route' => 'app_typography']);
        $this->addMenuItem($menu, ['route' => 'app_heroku']);
// https://dashboard.heroku.com/apps/agile-chamber-52782/resources
        // for nested menus, don't add a route, just a label, then use it for the argument to addMenuItem
        $nestedMenu = $this->addMenuItem($menu, ['label' => 'Credits']);
        foreach (['bundles', 'javascript'] as $type) {
            $this->addMenuItem($nestedMenu, [
                'route' => 'survos_base_credits', 'rp' => ['type' => $type], 'label' => ucfirst($type)]);
        }

        // add the login/logout menu items.
        $this->authMenu($this->security, $menu);

    }

    /*
    * @return array The event names to listen to
    */
    public static function getSubscribedEvents()
    {
        return [
            MenuBuilder::SIDEBAR_MENU_EVENT => 'onKnpMenuEvent',
        ];
    }
}