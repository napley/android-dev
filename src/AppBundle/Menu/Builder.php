<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {

        $menus = $this->container->get('app.menu')->getMenu();
        
        $menu = $factory->createItem('root');

        $menu->addChild('Accueil', array('route' => 'homepage'));
        
        $menu->addChild('Articles', array(
            'route' => 'home_article'
        ));
        foreach ($menus['typeA'] as $category) {
            $menu['Articles']->addChild($category->getNom(), array(
                'route' => 'article_cat',
                'routeParameters' => array('slug' => $category->getSlug())
            ));
        }

        $menu->addChild('Tutos', array(
            'route' => 'home_tuto'
        ));
        foreach ($menus['typeT'] as $category) {
            $menu['Tutos']->addChild($category->getNom(), array(
                'route' => 'tuto_cat',
                'routeParameters' => array('slug' => $category->getSlug())
            ));
        }

        $menu->addChild('Projets', array(
            'route' => 'home_projet'
        ));
        foreach ($menus['projets'] as $projet) {
            $menu['Projets']->addChild($projet->getNom(), array(
                'route' => 'projet_voir',
                'routeParameters' => array('slug' => $projet->getSlug())
            ));
        }
        
        $menu->setChildrenAttribute('class', 'menuzord-menu menuzord-right menuzord-indented scrollable');
        $menu['Articles']->setChildrenAttribute('class', 'dropdown');
        $menu['Tutos']->setChildrenAttribute('class', 'dropdown');
        $menu['Projets']->setChildrenAttribute('class', 'dropdown');
        
        switch ($options['current']) {
            case 'Accueil':
                $menu['Accueil']->setAttribute('class', 'active');
                break;
            case 'Articles':
                $menu['Articles']->setAttribute('class', 'active');
                break;
            case 'Tutos':
                $menu['Tutos']->setAttribute('class', 'active');
                break;
            case 'Projets':
                $menu['Projets']->setAttribute('class', 'active');
                break;

            default:
                break;
        }
        
        return $menu;
    }
}