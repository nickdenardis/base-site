<?php

namespace App\Repositories;

use Contracts\Repositories\NewsRepositoryContract;
use Illuminate\Cache\Repository;
use Waynestate\Api\Connector;
use Waynestate\Promotions\ParsePromos;

class NewsRepository implements NewsRepositoryContract
{
    /** @var Connector */
    protected $wsuApi;

    /** @var ParsePromos */
    protected $parsePromos;

    /** @var Repository */
    protected $cache;

    /**
     * Construct the NewsRepository.
     *
     * @param Connector $wsuApi
     * @param ParsePromos $parsePromos
     * @param Repository $cache
     */
    public function __construct(Connector $wsuApi, ParsePromos $parsePromos, Repository $cache)
    {
        $this->wsuApi = $wsuApi;
        $this->parsePromos = $parsePromos;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewsByDisplayOrder($site_id)
    {
        $params = [
            'method' => 'cms.news.listing',
            'sites' => [$site_id],
            'is_active' => '1',
            'limit' => 4,
            'order_by' => 'display_order',
            'sort' => 'ASC',
            'server_location' => 'both',
            'fields' => 'news_id|title|link|posted|app_id|slug',
            'before' => 'now',
        ];

        $news_listing = $this->cache->remember($params['method'].md5(serialize($params)), config('cache.ttl'), function () use ($params) {
            return $this->wsuApi->sendRequest($params['method'], $params);
        });

        // Make sure the return is an array
        $news['news'] = (is_array($news_listing) && array_key_exists('news', $news_listing)) ? $news_listing['news'] : [];

        return $news;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewsByPage($site_id, $page = 0, $limit = 25, $category_id = null)
    {
        $params = [
            'method' => 'cms.news.listing',
            'sites' => [$site_id],
            'is_active' => '1',
            'order_by' => 'posted',
            'sort' => 'DESC',
            'fields' => 'news_id|title|link|posted|app_id|slug|excerpt',
            'server_location' => 'both',
            'limit' => $limit,
            'archive' => '1',
            'offset' => ($page != '' ? $page * $limit : 0),
            'category_id' => $category_id !== null ? $category_id : '',
        ];

        return $this->cache->remember($params['method'].md5(serialize($params)), config('cache.ttl'), function () use ($params) {
            return $this->wsuApi->sendRequest($params['method'], $params);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getNewsItem($id, $site_id)
    {
        $params = [
            'method' => 'cms.news.info',
            'sites' => [$site_id],
            'news_id' => $id,
            'is_active' => '1',
            'order_by' => 'posted',
            'sort' => 'DESC',
            'fields' => 'news_id|title|link|posted|app_id|slug|excerpt|is_archive|ending|body',
            'server_location' => 'both',
            'limit' => 1,
        ];

        return $this->cache->remember($params['method'].md5(serialize($params)), config('cache.ttl'), function () use ($params) {
            return $this->wsuApi->sendRequest($params['method'], $params);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getPaging($page = 0, $limit = 25)
    {
        return [
            'paging' => [
                'perPage' => $limit,
                'page_number_previous' => $page + 1,
                'page_number_next' => $page - 1,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCategories($site_id)
    {
        $params = [
            'method' => 'cms.news.categories',
            'site_id' => $site_id,
            'is_active' => 1,
        ];

        $categories = $this->cache->remember($params['method'].md5(serialize($params)), config('cache.ttl'), function () use ($params) {
            return $this->wsuApi->sendRequest($params['method'], $params);
        });

        return $categories;
    }

    /**
     * {@inheritdoc}
     */
    public function setSelectedCategory($categories, $slug)
    {
        $categories['selected_news_category']['category_id'] = null;

        foreach ($categories['news_categories'] as $key => $category) {
            if ($category['slug'] == $slug) {
                $categories['selected_news_category'] = $category;
            }
        }

        return $categories;
    }
}
