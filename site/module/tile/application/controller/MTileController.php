<?php

class MTileController
{

    /**
     *
     * @param string $action            
     * @param FWebVoRequest $data            
     * @throws Exception
     * @return Ambigous <Ambigous, Singleton, FwkWebVoContent>|Ambigous <Singleton, FwkWebVoContent>
     */
    public function dispatch($action, $data)
    {
        if (FSecurityBoSecurity::getAuthenticated())
        {
            switch ($action)
            {
                case 'createTile':
                    $tileBo = new MTileBo();
                    return $tileBo->createTile($data);
                    break;
                case 'readTile':
                    $tileBo = new MTileBo();
                    
                    if (!empty($data->TileId))
                    {
                        return $tileBo->readTile($data);
                    }
                    else
                    {
                        return $tileBo->readTiles($data);
                    }
                    break;
                case 'updateTile':
                    $tileBo = new MTileBo();
                    return $tileBo->updateTile($data);
                    break;
                case 'deleteTile':
                    $tileBo = new MTileBo();
                    return $tileBo->deleteTile($data);
                    break;
                default:
                    $responseVo = FWebVoResponse::Singleton();
                    $responseVo->setMessage('Invalid action: ' . $action, FWebVoResponse::ERROR);
                    return $responseVo;
                    break;
            }
        }
        else
        {
            switch ($action)
            {
                case 'readTile':
                    $tileBo = new MTileBo();
                    
                    if (!empty($data->TileId))
                    {
                        return $tileBo->readTile($data);
                    }
                    break;
                
                default:
                    throw new Exception('Request is not authenticated.');
                    break;
            }
        }
    }
}