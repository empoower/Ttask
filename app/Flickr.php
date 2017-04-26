<?php

namespace App;

use GuzzleHttp\Client;

class Flickr
{
    const MAX_TITLE_LENTH = 25;
    const DEFAULT_LANGUAGE = 'en-us';
    const DEFAULT_RESPONSE_FORMAT = 'json';
    const NO_JSON_CALLBACK = '1'; // clear json in response
    const DEFAULT_TAG_MODE = 'all';
    const TAG_MODE_ALL = 'all';
    const TAG_MODE_ANY = 'any';
    const HTTP_200_OK = '200';

    private static $url = "https://api.flickr.com/services/feeds/photos_public.gne";

    private static $languages = [
        'de-de' => 'German',
        'en-us' => 'English',
        'es-us' => 'Spanish',
        'fr-fr' => 'French',
        'it-it' => 'Italian',
        'ko-kr' => 'Korean',
        'pt-br' => 'Portuguese',
        'zh-hk' => 'Chinese'
    ];

    private static $tagmodes = ['all', 'any'];

    /**
     * @return array
     */
    public static function getLanguages()
    {
        return self::$languages;
    }

    /**
     * @return string
     */
    final static function getFlickrPhotosPublicUrl()
    {
        return self::$url;
    }

    /**
     * @param $ids
     * @param $tags
     * @param $tagmode
     * @param string $language
     * @return mixed
     */
    public static function getResponse($ids, $tags, $tagmode, $language = self::DEFAULT_LANGUAGE)
    {
        $params = self::validateParams($ids, $tags, $tagmode, $language);
        $requestUrl = self::getFlickrPhotosPublicUrl() . "?" . http_build_query($params);
        $res = (new Client())->request('GET', $requestUrl);
        $statusCode = $res->getStatusCode();
        $contentFromUrl = $res->getBody()->getContents();

        $jsonData = json_decode($contentFromUrl, true);
        if (!$jsonData) {
            // fix for http://flickertask  sometimes response has invalid JSON
            $jsonData = json_decode(str_replace("\'", "'", $contentFromUrl), true);
        }

        if ($statusCode == self::HTTP_200_OK) {
            $jsonRequest = self::processData($jsonData);
            $jsonRequest['success'] = 'true';
            $jsonRequest['language'] = $language;
        } else {
            $jsonRequest['success'] = 'false';
        }
        return $jsonRequest;
    }

    /**
     * @param $language
     * @return string
     */
    private static function checkLanguage($language)
    {
        return (in_array($language, array_flip(self::$languages))) ? $language : self::DEFAULT_LANGUAGE;
    }

    /**
     * @param $tagmode
     * @return string
     */
    private static function checkTagmode($tagmode)
    {
        return (in_array($tagmode, self::$tagmodes)) ? $tagmode : self::TAG_MODE_ANY;
    }

    /**
     * @param $ids
     * @param $tags
     * @param $tagmode
     * @param $language
     * @return array
     */
    private static function validateParams($ids, $tags, $tagmode, $language)
    {
        $params = [
            'format' => self::DEFAULT_RESPONSE_FORMAT,
            'nojsoncallback' => self::NO_JSON_CALLBACK,
            'lang' => self::checkLanguage($language),
            'tagmode' => self::checkTagmode($tagmode)
        ];

        if (!empty($ids)) {
            if (preg_match("#^[aA-zZ0-9\-_@,]+$#", $ids)) {
                $idsArr = explode(',', $ids);
                if (count($idsArr) == 1) {
                    $params['id'] = $ids;
                } else {
                    $params['ids'] = $ids;
                }
            } else {
                $error[] = "There are illegal characters in IDs";
            }
        }

        if (!empty($tags)) {
            $tags = implode(',', $tags);
            $tags = str_replace(" ", "", $tags);
            if (preg_match("#^[aA-zZ0-9\-_,]+$#", $tags)) {
                $params['tags'] = $tags;
            } else {
                $error[] = "There are illegal characters in tags";
            }
        }

        return $params;
    }

    /**
     * @param $jsonData
     * @return mixed
     */
    private static function processData($jsonData)
    {
        if (!empty($jsonData['items'])) {
            $authors = [];
            foreach ($jsonData['items'] as $key => $item) {
                $author = self::getAuthorName($item['author']);
                $authors[] = $author;
                $shortTitle = self::getTitle($item['title']);
                /**
                 * Html Block for main pictures
                 */
                $jsonData['items'][$key]['imageDivS'] = '
                        <div class="mbr-gallery-item mbr-gallery-item__mobirise3 mbr-gallery-item--p1"
                             data-tags="' . $author . '" data-video-url="false" id="pic_m_' . $key . '">
                            <div href="#lb-gallery4-7" data-slide-to="0" data-toggle="modal">
                                <img alt="' . $item['title'] . '" src="' . $item['media']['m'] . '">
                                <span class="icon-focus"></span>
                                <span class="mbr-gallery-title">' . $shortTitle . '</span>
                            </div>
                        </div>';

                $imageDivB = self::getFullImage($item['media']['m']);

                /**
                 * Html Block for carousel(zoom) pictures
                 */
                $carouselItem = ($key == 0) ? '<div class="carousel-item active" id="pic_b_' . $key . '">' : '<div class="carousel-item" id="pic_b_' . $key . '">';
                $jsonData['items'][$key]['imageDivB'] = $carouselItem . '
                     <img alt="' . $item['title'] . '" src="' . $imageDivB . '">
                     </div>';

            }

            $jsonData['authors'] = array_unique($authors, SORT_STRING);

        }

        return $jsonData;

    }

    /**
     * @param $m
     * @return mixed
     *
     * If you need to get full image, you need to change file name. For example:
     * https:\/\/farm3.staticflickr.com\/2946\/34222087306_92865a2a1b_m.jpg - small images
     * https:\/\/farm3.staticflickr.com\/2946\/34222087306_92865a2a1b_b.jpg - big images
     *
     */
    private static function getFullImage($m)
    {
        $parsedUrl = parse_url($m, PHP_URL_PATH);
        $imgM = basename($parsedUrl);
        $imgB = str_replace('_m.', '_b.', $imgM);
        return str_replace($imgM, $imgB, $m);
    }

    /**
     * @param $author
     * @return array|mixed
     *
     * Get author name from string
     * before: "author": "nobody@flickr.com (\"PurpleHousePhotos\")",
     * after: "PurpleHousePhotos",
     */
    private static function getAuthorName($author)
    {
        if (!empty($author)) {
            $matches = [];
            //match everything in ()
            $pattern = "/([^\)]+)\((.*)\)/";

            $autors = [];
            if (preg_match($pattern, $author, $matches)) {
                $autors[] = preg_replace('/(^"|"$)/', '', $matches[2]);
            }
            // clear string
            $autors = preg_replace('/[^A-Za-z0-9\- _]/', '', reset($autors));
            return $autors;
        }

    }

    /**
     * @param $title
     * @return string
     *
     * Sometimes the length of the string can be very long
     * To avoid overflow of the block of the picture with text, it is necessary to make a limit.
     * In this case, the string will be truncated if the number of characters is greater than 25
     */
    private static function getTitle($title)
    {
        if (strlen($title) > self::MAX_TITLE_LENTH) {
            return mb_substr($title, 0, self::MAX_TITLE_LENTH) . ' ...';
        } else {
            return $title;
        }

    }
}
