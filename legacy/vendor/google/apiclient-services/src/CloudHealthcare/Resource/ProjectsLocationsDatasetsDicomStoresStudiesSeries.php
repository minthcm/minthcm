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

namespace Google\Service\CloudHealthcare\Resource;

use Google\Service\CloudHealthcare\HttpBody;
use Google\Service\CloudHealthcare\Operation;

/**
 * The "series" collection of methods.
 * Typical usage is:
 *  <code>
 *   $healthcareService = new Google\Service\CloudHealthcare(...);
 *   $series = $healthcareService->projects_locations_datasets_dicomStores_studies_series;
 *  </code>
 */
class ProjectsLocationsDatasetsDicomStoresStudiesSeries extends \Google\Service\Resource
{
  /**
   * DeleteSeries deletes all instances within the given study and series. Delete
   * requests are equivalent to the GET requests specified in the Retrieve
   * transaction. The method returns an Operation which will be marked successful
   * when the deletion is complete. Warning: Instances cannot be inserted into a
   * series that is being deleted by an operation until the operation completes.
   * For samples that show how to call DeleteSeries, see [Delete a study, series,
   * or instance](https://cloud.google.com/healthcare/docs/how-
   * tos/dicomweb#delete-dicom). (series.delete)
   *
   * @param string $parent Required. The name of the DICOM store that is being
   * accessed. For example, `projects/{project_id}/locations/{location_id}/dataset
   * s/{dataset_id}/dicomStores/{dicom_store_id}`.
   * @param string $dicomWebPath Required. The path of the DeleteSeries request.
   * For example, `studies/{study_uid}/series/{series_uid}`.
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function delete($parent, $dicomWebPath, $optParams = [])
  {
    $params = ['parent' => $parent, 'dicomWebPath' => $dicomWebPath];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], Operation::class);
  }
  /**
   * RetrieveSeriesMetadata returns instance associated with the given study and
   * series, presented as metadata with the bulk data removed. See
   * [RetrieveTransaction] (http://dicom.nema.org/medical/dicom/current/output/htm
   * l/part18.html#sect_10.4). For details on the implementation of
   * RetrieveSeriesMetadata, see [Metadata
   * resources](https://cloud.google.com/healthcare/docs/dicom#metadata_resources)
   * in the Cloud Healthcare API conformance statement. For samples that show how
   * to call RetrieveSeriesMetadata, see [Retrieve
   * metadata](https://cloud.google.com/healthcare/docs/how-tos/dicomweb#retrieve-
   * metadata). (series.retrieveMetadata)
   *
   * @param string $parent Required. The name of the DICOM store that is being
   * accessed. For example, `projects/{project_id}/locations/{location_id}/dataset
   * s/{dataset_id}/dicomStores/{dicom_store_id}`.
   * @param string $dicomWebPath Required. The path of the RetrieveSeriesMetadata
   * DICOMweb request. For example,
   * `studies/{study_uid}/series/{series_uid}/metadata`.
   * @param array $optParams Optional parameters.
   * @return HttpBody
   * @throws \Google\Service\Exception
   */
  public function retrieveMetadata($parent, $dicomWebPath, $optParams = [])
  {
    $params = ['parent' => $parent, 'dicomWebPath' => $dicomWebPath];
    $params = array_merge($params, $optParams);
    return $this->call('retrieveMetadata', [$params], HttpBody::class);
  }
  /**
   * RetrieveSeries returns all instances within the given study and series. See
   * [RetrieveTransaction] (http://dicom.nema.org/medical/dicom/current/output/htm
   * l/part18.html#sect_10.4). For details on the implementation of
   * RetrieveSeries, see [DICOM study/series/instances](https://cloud.google.com/h
   * ealthcare/docs/dicom#dicom_studyseriesinstances) in the Cloud Healthcare API
   * conformance statement. For samples that show how to call RetrieveSeries, see
   * [Retrieve DICOM data](https://cloud.google.com/healthcare/docs/how-
   * tos/dicomweb#retrieve-dicom). (series.retrieveSeries)
   *
   * @param string $parent Required. The name of the DICOM store that is being
   * accessed. For example, `projects/{project_id}/locations/{location_id}/dataset
   * s/{dataset_id}/dicomStores/{dicom_store_id}`.
   * @param string $dicomWebPath Required. The path of the RetrieveSeries DICOMweb
   * request. For example, `studies/{study_uid}/series/{series_uid}`.
   * @param array $optParams Optional parameters.
   * @return HttpBody
   * @throws \Google\Service\Exception
   */
  public function retrieveSeries($parent, $dicomWebPath, $optParams = [])
  {
    $params = ['parent' => $parent, 'dicomWebPath' => $dicomWebPath];
    $params = array_merge($params, $optParams);
    return $this->call('retrieveSeries', [$params], HttpBody::class);
  }
  /**
   * SearchForInstances returns a list of matching instances. See [Search
   * Transaction] (http://dicom.nema.org/medical/dicom/current/output/html/part18.
   * html#sect_10.6). For details on the implementation of SearchForInstances, see
   * [Search transaction](https://cloud.google.com/healthcare/docs/dicom#search_tr
   * ansaction) in the Cloud Healthcare API conformance statement. For samples
   * that show how to call SearchForInstances, see [Search for DICOM
   * data](https://cloud.google.com/healthcare/docs/how-tos/dicomweb#search-
   * dicom). (series.searchForInstances)
   *
   * @param string $parent Required. The name of the DICOM store that is being
   * accessed. For example, `projects/{project_id}/locations/{location_id}/dataset
   * s/{dataset_id}/dicomStores/{dicom_store_id}`.
   * @param string $dicomWebPath Required. The path of the
   * SearchForInstancesRequest DICOMweb request. For example, `instances`,
   * `series/{series_uid}/instances`, or `studies/{study_uid}/instances`.
   * @param array $optParams Optional parameters.
   * @return HttpBody
   * @throws \Google\Service\Exception
   */
  public function searchForInstances($parent, $dicomWebPath, $optParams = [])
  {
    $params = ['parent' => $parent, 'dicomWebPath' => $dicomWebPath];
    $params = array_merge($params, $optParams);
    return $this->call('searchForInstances', [$params], HttpBody::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsDatasetsDicomStoresStudiesSeries::class, 'Google_Service_CloudHealthcare_Resource_ProjectsLocationsDatasetsDicomStoresStudiesSeries');
