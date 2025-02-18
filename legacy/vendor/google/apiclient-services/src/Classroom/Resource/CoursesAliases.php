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

namespace Google\Service\Classroom\Resource;

use Google\Service\Classroom\ClassroomEmpty;
use Google\Service\Classroom\CourseAlias;
use Google\Service\Classroom\ListCourseAliasesResponse;

/**
 * The "aliases" collection of methods.
 * Typical usage is:
 *  <code>
 *   $classroomService = new Google\Service\Classroom(...);
 *   $aliases = $classroomService->courses_aliases;
 *  </code>
 */
class CoursesAliases extends \Google\Service\Resource
{
  /**
   * Creates an alias for a course. This method returns the following error codes:
   * * `PERMISSION_DENIED` if the requesting user is not permitted to create the
   * alias or for access errors. * `NOT_FOUND` if the course does not exist. *
   * `ALREADY_EXISTS` if the alias already exists. * `FAILED_PRECONDITION` if the
   * alias requested does not make sense for the requesting user or course (for
   * example, if a user not in a domain attempts to access a domain-scoped alias).
   * (aliases.create)
   *
   * @param string $courseId Identifier of the course to alias. This identifier
   * can be either the Classroom-assigned identifier or an alias.
   * @param CourseAlias $postBody
   * @param array $optParams Optional parameters.
   * @return CourseAlias
   * @throws \Google\Service\Exception
   */
  public function create($courseId, CourseAlias $postBody, $optParams = [])
  {
    $params = ['courseId' => $courseId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], CourseAlias::class);
  }
  /**
   * Deletes an alias of a course. This method returns the following error codes:
   * * `PERMISSION_DENIED` if the requesting user is not permitted to remove the
   * alias or for access errors. * `NOT_FOUND` if the alias does not exist. *
   * `FAILED_PRECONDITION` if the alias requested does not make sense for the
   * requesting user or course (for example, if a user not in a domain attempts to
   * delete a domain-scoped alias). (aliases.delete)
   *
   * @param string $courseId Identifier of the course whose alias should be
   * deleted. This identifier can be either the Classroom-assigned identifier or
   * an alias.
   * @param string $alias Alias to delete. This may not be the Classroom-assigned
   * identifier.
   * @param array $optParams Optional parameters.
   * @return ClassroomEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($courseId, $alias, $optParams = [])
  {
    $params = ['courseId' => $courseId, 'alias' => $alias];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], ClassroomEmpty::class);
  }
  /**
   * Returns a list of aliases for a course. This method returns the following
   * error codes: * `PERMISSION_DENIED` if the requesting user is not permitted to
   * access the course or for access errors. * `NOT_FOUND` if the course does not
   * exist. (aliases.listCoursesAliases)
   *
   * @param string $courseId The identifier of the course. This identifier can be
   * either the Classroom-assigned identifier or an alias.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize Maximum number of items to return. Zero or
   * unspecified indicates that the server may assign a maximum. The server may
   * return fewer than the specified number of results.
   * @opt_param string pageToken nextPageToken value returned from a previous list
   * call, indicating that the subsequent page of results should be returned. The
   * list request must be otherwise identical to the one that resulted in this
   * token.
   * @return ListCourseAliasesResponse
   * @throws \Google\Service\Exception
   */
  public function listCoursesAliases($courseId, $optParams = [])
  {
    $params = ['courseId' => $courseId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListCourseAliasesResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CoursesAliases::class, 'Google_Service_Classroom_Resource_CoursesAliases');
