<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\GamesManagement;

class HiddenPlayer extends \Google\Model
{
  /**
   * @var string
   */
  public $hiddenTimeMillis;
  /**
   * @var string
   */
  public $kind;
  protected $playerType = Player::class;
  protected $playerDataType = '';

  /**
   * @param string
   */
  public function setHiddenTimeMillis($hiddenTimeMillis)
  {
    $this->hiddenTimeMillis = $hiddenTimeMillis;
  }
  /**
   * @return string
   */
  public function getHiddenTimeMillis()
  {
    return $this->hiddenTimeMillis;
  }
  /**
   * @param string
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param Player
   */
  public function setPlayer(Player $player)
  {
    $this->player = $player;
  }
  /**
   * @return Player
   */
  public function getPlayer()
  {
    return $this->player;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(HiddenPlayer::class, 'Google_Service_GamesManagement_HiddenPlayer');
