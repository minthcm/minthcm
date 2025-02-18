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

namespace Google\Service\Vision\Resource;

use Google\Service\Vision\ListProductsResponse;
use Google\Service\Vision\Operation;
use Google\Service\Vision\Product;
use Google\Service\Vision\PurgeProductsRequest;
use Google\Service\Vision\VisionEmpty;

/**
 * The "products" collection of methods.
 * Typical usage is:
 *  <code>
 *   $visionService = new Google\Service\Vision(...);
 *   $products = $visionService->projects_locations_products;
 *  </code>
 */
class ProjectsLocationsProducts extends \Google\Service\Resource
{
  /**
   * Creates and returns a new product resource. Possible errors: * Returns
   * INVALID_ARGUMENT if display_name is missing or longer than 4096 characters. *
   * Returns INVALID_ARGUMENT if description is longer than 4096 characters. *
   * Returns INVALID_ARGUMENT if product_category is missing or invalid.
   * (products.create)
   *
   * @param string $parent Required. The project in which the Product should be
   * created. Format is `projects/PROJECT_ID/locations/LOC_ID`.
   * @param Product $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string productId A user-supplied resource id for this Product. If
   * set, the server will attempt to use this value as the resource id. If it is
   * already in use, an error is returned with code ALREADY_EXISTS. Must be at
   * most 128 characters long. It cannot contain the character `/`.
   * @return Product
   * @throws \Google\Service\Exception
   */
  public function create($parent, Product $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Product::class);
  }
  /**
   * Permanently deletes a product and its reference images. Metadata of the
   * product and all its images will be deleted right away, but search queries
   * against ProductSets containing the product may still work until all related
   * caches are refreshed. (products.delete)
   *
   * @param string $name Required. Resource name of product to delete. Format is:
   * `projects/PROJECT_ID/locations/LOC_ID/products/PRODUCT_ID`
   * @param array $optParams Optional parameters.
   * @return VisionEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], VisionEmpty::class);
  }
  /**
   * Gets information associated with a Product. Possible errors: * Returns
   * NOT_FOUND if the Product does not exist. (products.get)
   *
   * @param string $name Required. Resource name of the Product to get. Format is:
   * `projects/PROJECT_ID/locations/LOC_ID/products/PRODUCT_ID`
   * @param array $optParams Optional parameters.
   * @return Product
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Product::class);
  }
  /**
   * Lists products in an unspecified order. Possible errors: * Returns
   * INVALID_ARGUMENT if page_size is greater than 100 or less than 1.
   * (products.listProjectsLocationsProducts)
   *
   * @param string $parent Required. The project OR ProductSet from which Products
   * should be listed. Format: `projects/PROJECT_ID/locations/LOC_ID`
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize The maximum number of items to return. Default 10,
   * maximum 100.
   * @opt_param string pageToken The next_page_token returned from a previous List
   * request, if any.
   * @return ListProductsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsProducts($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListProductsResponse::class);
  }
  /**
   * Makes changes to a Product resource. Only the `display_name`, `description`,
   * and `labels` fields can be updated right now. If labels are updated, the
   * change will not be reflected in queries until the next index time. Possible
   * errors: * Returns NOT_FOUND if the Product does not exist. * Returns
   * INVALID_ARGUMENT if display_name is present in update_mask but is missing
   * from the request or longer than 4096 characters. * Returns INVALID_ARGUMENT
   * if description is present in update_mask but is longer than 4096 characters.
   * * Returns INVALID_ARGUMENT if product_category is present in update_mask.
   * (products.patch)
   *
   * @param string $name The resource name of the product. Format is:
   * `projects/PROJECT_ID/locations/LOC_ID/products/PRODUCT_ID`. This field is
   * ignored when creating a product.
   * @param Product $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask The FieldMask that specifies which fields to
   * update. If update_mask isn't specified, all mutable fields are to be updated.
   * Valid mask paths include `product_labels`, `display_name`, and `description`.
   * @return Product
   * @throws \Google\Service\Exception
   */
  public function patch($name, Product $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Product::class);
  }
  /**
   * Asynchronous API to delete all Products in a ProductSet or all Products that
   * are in no ProductSet. If a Product is a member of the specified ProductSet in
   * addition to other ProductSets, the Product will still be deleted. It is
   * recommended to not delete the specified ProductSet until after this operation
   * has completed. It is also recommended to not add any of the Products involved
   * in the batch delete to a new ProductSet while this operation is running
   * because those Products may still end up deleted. It's not possible to undo
   * the PurgeProducts operation. Therefore, it is recommended to keep the csv
   * files used in ImportProductSets (if that was how you originally built the
   * Product Set) before starting PurgeProducts, in case you need to re-import the
   * data after deletion. If the plan is to purge all of the Products from a
   * ProductSet and then re-use the empty ProductSet to re-import new Products
   * into the empty ProductSet, you must wait until the PurgeProducts operation
   * has finished for that ProductSet. The google.longrunning.Operation API can be
   * used to keep track of the progress and results of the request.
   * `Operation.metadata` contains `BatchOperationMetadata`. (progress)
   * (products.purge)
   *
   * @param string $parent Required. The project and location in which the
   * Products should be deleted. Format is `projects/PROJECT_ID/locations/LOC_ID`.
   * @param PurgeProductsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function purge($parent, PurgeProductsRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('purge', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsProducts::class, 'Google_Service_Vision_Resource_ProjectsLocationsProducts');
