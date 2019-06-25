<?php

namespace App\AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\ArticleCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class MenuBuilder
{
    private $factory;
    private $em;
    private $cache;
    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, EntityManagerInterface $em)
    {
        $this->factory = $factory;
        $this->em = $em;
        $this->cache = new FilesystemAdapter();
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
        $cachedMenu = $this->cache->getItem($cacheId);
        if (!$cachedMenu->isHit()){
            $categories = $this->getCategories();
            $menu = $this->factory->createItem('root');
            $menu->addChild('Home', ['route' => 'app_home']);
            $menu->addChild('Latest', ['route' => 'app_article_latest']);
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
            $cachedMenu->set($menu);
            $this->cache->save($cachedMenu);
        }
        $menu = $cachedMenu->get($cacheId);
        return $menu;
    }

}