<?php

namespace App\AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\ArticleCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MenuBuilder
{
    private $factory;
    private $em;
    private $cache;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, EntityManagerInterface $em, AdapterInterface $cache)
    {
        $this->factory = $factory;
        $this->em = $em;
        $this->cache = $cache;
    }

    private function getCategories()
    {
        $repository = $this->em->getRepository(ArticleCategory::class);
        $categories = $repository->findAll();
        return $categories;
    }

    public function createMainMenu(RequestStack $requestStack)
    {
        $cacheId = "top_menu";
        $item = $this->cache->getItem($cacheId);
        if (!$item->isHit()) {
            $categories = $this->getCategories();
            $menu = $this->factory->createItem('root');
            $menu->addChild('Home', ['route' => 'app_home']);
            $menu->addChild('Latest');
            $menu->addChild('Latest');
            $cat = $menu->addChild('Categories');
            if (!empty($categories)) {
                foreach ($categories as $catData) {
                    $cat->addChild(
                        $catData->getName(),
                        [
                            'route' => 'app_cat_detail',
                            'routeParameters' => [
                                'catCode' => $catData->getCode(),
                            ]
                        ]
                    );
                }
            }
            $content = $menu;
            $item->set($content);
            $this->cache->save($item);
        }
        $menu = $item->get();

        return $menu;
    }

}