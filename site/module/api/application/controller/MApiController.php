<?php

class MApiController extends FControllerBoController
{

    /**
     * This is the main controller for handling the external API calls.
     *
     * @see FControllerBoController::dispatch()
     */
    public function dispatch($action, $data)
    {
        switch ($action) {
            case 'getLocationsForBSSIDs':
                $locationBo = new MApiBoLocation();
                return $locationBo->getLocationsForBssids($data);
            
            case 'getLocationCountForBSSIDs':
                $locationBo = new MApiBoLocation();
                return $locationBo->getLocationsCount($data);
            
            case 'getFavoriteLocationsForDevice':
                $locationBo = new MApiBoLocation();
                return $locationBo->getFavoriteLocations($data);
            
            case 'updateLocationFavoriteStatus':
                $locationBo = new MApiBoLocation();
                return $locationBo->updateLocationFavoriteStatus($data);
            
            case 'isLocationFavorite':
                $locationBo = new MApiBoLocation();
                return $locationBo->isLocationFavorite($data);
            
            case 'registerDevice':
                $userBo = new MApiBoUser();
                return $userBo->registerDevice($data);
            
            case 'getLocationFavoriteCount':
                $locationBo = new MApiBoLocation();
                return $locationBo->getLocationFavoriteCount($data);
            
            case 'getTilesForLocation':
                $locationBo = new MApiBoLocation();
                return $locationBo->getTilesForLocation($data);
            
            case 'getTileFavoriteCount':
                $locationBo = new MApiBoLocation();
                return $locationBo->getTileFavoriteCount($data);
            
            case 'updateTileFavoriteStatus':
                $locationBo = new MApiBoLocation();
                return $locationBo->updateTileFavoriteStatus($data);
            
            case 'isTileFavorite':
                $locationBo = new MApiBoLocation();
                return $locationBo->isTileFavorite($data);
            
            case 'registerUserTileVisit':
                $locationBo = new MApiBoLocation();
                return $locationBo->registerUserTileVisit($data);
            
            case 'registerUserLocationVisit':
                $locationBo = new MApiBoLocation();
                return $locationBo->registerUserLocationVisit($data);
            
            case 'registerUserLocationPassBy':
                $locationBo = new MApiBoLocation();
                return $locationBo->registerUserLocationPassBy($data);
                
            case 'getLocationHours':
                $locationBo = new MApiBoLocation();
                return $locationBo->getLocationHours($data);
            
            default:
                break;
        }
    }
}