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

namespace Google\Service\Dataproc;

class OrderedJob extends \Google\Collection
{
  protected $collection_key = 'prerequisiteStepIds';
  protected $flinkJobType = FlinkJob::class;
  protected $flinkJobDataType = '';
  protected $hadoopJobType = HadoopJob::class;
  protected $hadoopJobDataType = '';
  protected $hiveJobType = HiveJob::class;
  protected $hiveJobDataType = '';
  /**
   * @var string[]
   */
  public $labels;
  protected $pigJobType = PigJob::class;
  protected $pigJobDataType = '';
  /**
   * @var string[]
   */
  public $prerequisiteStepIds;
  protected $prestoJobType = PrestoJob::class;
  protected $prestoJobDataType = '';
  protected $pysparkJobType = PySparkJob::class;
  protected $pysparkJobDataType = '';
  protected $schedulingType = JobScheduling::class;
  protected $schedulingDataType = '';
  protected $sparkJobType = SparkJob::class;
  protected $sparkJobDataType = '';
  protected $sparkRJobType = SparkRJob::class;
  protected $sparkRJobDataType = '';
  protected $sparkSqlJobType = SparkSqlJob::class;
  protected $sparkSqlJobDataType = '';
  /**
   * @var string
   */
  public $stepId;
  protected $trinoJobType = TrinoJob::class;
  protected $trinoJobDataType = '';

  /**
   * @param FlinkJob
   */
  public function setFlinkJob(FlinkJob $flinkJob)
  {
    $this->flinkJob = $flinkJob;
  }
  /**
   * @return FlinkJob
   */
  public function getFlinkJob()
  {
    return $this->flinkJob;
  }
  /**
   * @param HadoopJob
   */
  public function setHadoopJob(HadoopJob $hadoopJob)
  {
    $this->hadoopJob = $hadoopJob;
  }
  /**
   * @return HadoopJob
   */
  public function getHadoopJob()
  {
    return $this->hadoopJob;
  }
  /**
   * @param HiveJob
   */
  public function setHiveJob(HiveJob $hiveJob)
  {
    $this->hiveJob = $hiveJob;
  }
  /**
   * @return HiveJob
   */
  public function getHiveJob()
  {
    return $this->hiveJob;
  }
  /**
   * @param string[]
   */
  public function setLabels($labels)
  {
    $this->labels = $labels;
  }
  /**
   * @return string[]
   */
  public function getLabels()
  {
    return $this->labels;
  }
  /**
   * @param PigJob
   */
  public function setPigJob(PigJob $pigJob)
  {
    $this->pigJob = $pigJob;
  }
  /**
   * @return PigJob
   */
  public function getPigJob()
  {
    return $this->pigJob;
  }
  /**
   * @param string[]
   */
  public function setPrerequisiteStepIds($prerequisiteStepIds)
  {
    $this->prerequisiteStepIds = $prerequisiteStepIds;
  }
  /**
   * @return string[]
   */
  public function getPrerequisiteStepIds()
  {
    return $this->prerequisiteStepIds;
  }
  /**
   * @param PrestoJob
   */
  public function setPrestoJob(PrestoJob $prestoJob)
  {
    $this->prestoJob = $prestoJob;
  }
  /**
   * @return PrestoJob
   */
  public function getPrestoJob()
  {
    return $this->prestoJob;
  }
  /**
   * @param PySparkJob
   */
  public function setPysparkJob(PySparkJob $pysparkJob)
  {
    $this->pysparkJob = $pysparkJob;
  }
  /**
   * @return PySparkJob
   */
  public function getPysparkJob()
  {
    return $this->pysparkJob;
  }
  /**
   * @param JobScheduling
   */
  public function setScheduling(JobScheduling $scheduling)
  {
    $this->scheduling = $scheduling;
  }
  /**
   * @return JobScheduling
   */
  public function getScheduling()
  {
    return $this->scheduling;
  }
  /**
   * @param SparkJob
   */
  public function setSparkJob(SparkJob $sparkJob)
  {
    $this->sparkJob = $sparkJob;
  }
  /**
   * @return SparkJob
   */
  public function getSparkJob()
  {
    return $this->sparkJob;
  }
  /**
   * @param SparkRJob
   */
  public function setSparkRJob(SparkRJob $sparkRJob)
  {
    $this->sparkRJob = $sparkRJob;
  }
  /**
   * @return SparkRJob
   */
  public function getSparkRJob()
  {
    return $this->sparkRJob;
  }
  /**
   * @param SparkSqlJob
   */
  public function setSparkSqlJob(SparkSqlJob $sparkSqlJob)
  {
    $this->sparkSqlJob = $sparkSqlJob;
  }
  /**
   * @return SparkSqlJob
   */
  public function getSparkSqlJob()
  {
    return $this->sparkSqlJob;
  }
  /**
   * @param string
   */
  public function setStepId($stepId)
  {
    $this->stepId = $stepId;
  }
  /**
   * @return string
   */
  public function getStepId()
  {
    return $this->stepId;
  }
  /**
   * @param TrinoJob
   */
  public function setTrinoJob(TrinoJob $trinoJob)
  {
    $this->trinoJob = $trinoJob;
  }
  /**
   * @return TrinoJob
   */
  public function getTrinoJob()
  {
    return $this->trinoJob;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OrderedJob::class, 'Google_Service_Dataproc_OrderedJob');
