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

namespace Google\Service\ShoppingContent;

class ShipmentInvoice extends \Google\Collection
{
  protected $collection_key = 'lineItemInvoices';
  protected $invoiceSummaryType = InvoiceSummary::class;
  protected $invoiceSummaryDataType = '';
  protected $lineItemInvoicesType = ShipmentInvoiceLineItemInvoice::class;
  protected $lineItemInvoicesDataType = 'array';
  /**
   * @var string
   */
  public $shipmentGroupId;

  /**
   * @param InvoiceSummary
   */
  public function setInvoiceSummary(InvoiceSummary $invoiceSummary)
  {
    $this->invoiceSummary = $invoiceSummary;
  }
  /**
   * @return InvoiceSummary
   */
  public function getInvoiceSummary()
  {
    return $this->invoiceSummary;
  }
  /**
   * @param ShipmentInvoiceLineItemInvoice[]
   */
  public function setLineItemInvoices($lineItemInvoices)
  {
    $this->lineItemInvoices = $lineItemInvoices;
  }
  /**
   * @return ShipmentInvoiceLineItemInvoice[]
   */
  public function getLineItemInvoices()
  {
    return $this->lineItemInvoices;
  }
  /**
   * @param string
   */
  public function setShipmentGroupId($shipmentGroupId)
  {
    $this->shipmentGroupId = $shipmentGroupId;
  }
  /**
   * @return string
   */
  public function getShipmentGroupId()
  {
    return $this->shipmentGroupId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ShipmentInvoice::class, 'Google_Service_ShoppingContent_ShipmentInvoice');
