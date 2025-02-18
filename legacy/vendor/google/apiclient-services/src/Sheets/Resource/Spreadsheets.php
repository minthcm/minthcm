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

namespace Google\Service\Sheets\Resource;

use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Google\Service\Sheets\BatchUpdateSpreadsheetResponse;
use Google\Service\Sheets\GetSpreadsheetByDataFilterRequest;
use Google\Service\Sheets\Spreadsheet;

/**
 * The "spreadsheets" collection of methods.
 * Typical usage is:
 *  <code>
 *   $sheetsService = new Google\Service\Sheets(...);
 *   $spreadsheets = $sheetsService->spreadsheets;
 *  </code>
 */
class Spreadsheets extends \Google\Service\Resource
{
  /**
   * Applies one or more updates to the spreadsheet. Each request is validated
   * before being applied. If any request is not valid then the entire request
   * will fail and nothing will be applied. Some requests have replies to give you
   * some information about how they are applied. The replies will mirror the
   * requests. For example, if you applied 4 updates and the 3rd one had a reply,
   * then the response will have 2 empty replies, the actual reply, and another
   * empty reply, in that order. Due to the collaborative nature of spreadsheets,
   * it is not guaranteed that the spreadsheet will reflect exactly your changes
   * after this completes, however it is guaranteed that the updates in the
   * request will be applied together atomically. Your changes may be altered with
   * respect to collaborator changes. If there are no collaborators, the
   * spreadsheet should reflect your changes. (spreadsheets.batchUpdate)
   *
   * @param string $spreadsheetId The spreadsheet to apply the updates to.
   * @param BatchUpdateSpreadsheetRequest $postBody
   * @param array $optParams Optional parameters.
   * @return BatchUpdateSpreadsheetResponse
   * @throws \Google\Service\Exception
   */
  public function batchUpdate($spreadsheetId, BatchUpdateSpreadsheetRequest $postBody, $optParams = [])
  {
    $params = ['spreadsheetId' => $spreadsheetId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('batchUpdate', [$params], BatchUpdateSpreadsheetResponse::class);
  }
  /**
   * Creates a spreadsheet, returning the newly created spreadsheet.
   * (spreadsheets.create)
   *
   * @param Spreadsheet $postBody
   * @param array $optParams Optional parameters.
   * @return Spreadsheet
   * @throws \Google\Service\Exception
   */
  public function create(Spreadsheet $postBody, $optParams = [])
  {
    $params = ['postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Spreadsheet::class);
  }
  /**
   * Returns the spreadsheet at the given ID. The caller must specify the
   * spreadsheet ID. By default, data within grids is not returned. You can
   * include grid data in one of 2 ways: * Specify a [field
   * mask](https://developers.google.com/sheets/api/guides/field-masks) listing
   * your desired fields using the `fields` URL parameter in HTTP * Set the
   * includeGridData URL parameter to true. If a field mask is set, the
   * `includeGridData` parameter is ignored For large spreadsheets, as a best
   * practice, retrieve only the specific spreadsheet fields that you want. To
   * retrieve only subsets of spreadsheet data, use the ranges URL parameter.
   * Ranges are specified using [A1 notation](/sheets/api/guides/concepts#cell).
   * You can define a single cell (for example, `A1`) or multiple cells (for
   * example, `A1:D5`). You can also get cells from other sheets within the same
   * spreadsheet (for example, `Sheet2!A1:C4`) or retrieve multiple ranges at once
   * (for example, `?ranges=A1:D5&ranges=Sheet2!A1:C4`). Limiting the range
   * returns only the portions of the spreadsheet that intersect the requested
   * ranges. (spreadsheets.get)
   *
   * @param string $spreadsheetId The spreadsheet to request.
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool includeGridData True if grid data should be returned. This
   * parameter is ignored if a field mask was set in the request.
   * @opt_param string ranges The ranges to retrieve from the spreadsheet.
   * @return Spreadsheet
   * @throws \Google\Service\Exception
   */
  public function get($spreadsheetId, $optParams = [])
  {
    $params = ['spreadsheetId' => $spreadsheetId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Spreadsheet::class);
  }
  /**
   * Returns the spreadsheet at the given ID. The caller must specify the
   * spreadsheet ID. This method differs from GetSpreadsheet in that it allows
   * selecting which subsets of spreadsheet data to return by specifying a
   * dataFilters parameter. Multiple DataFilters can be specified. Specifying one
   * or more data filters returns the portions of the spreadsheet that intersect
   * ranges matched by any of the filters. By default, data within grids is not
   * returned. You can include grid data one of 2 ways: * Specify a [field
   * mask](https://developers.google.com/sheets/api/guides/field-masks) listing
   * your desired fields using the `fields` URL parameter in HTTP * Set the
   * includeGridData parameter to true. If a field mask is set, the
   * `includeGridData` parameter is ignored For large spreadsheets, as a best
   * practice, retrieve only the specific spreadsheet fields that you want.
   * (spreadsheets.getByDataFilter)
   *
   * @param string $spreadsheetId The spreadsheet to request.
   * @param GetSpreadsheetByDataFilterRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Spreadsheet
   * @throws \Google\Service\Exception
   */
  public function getByDataFilter($spreadsheetId, GetSpreadsheetByDataFilterRequest $postBody, $optParams = [])
  {
    $params = ['spreadsheetId' => $spreadsheetId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('getByDataFilter', [$params], Spreadsheet::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Spreadsheets::class, 'Google_Service_Sheets_Resource_Spreadsheets');
