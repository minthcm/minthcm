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

namespace Google\Service\Firestore\Resource;

use Google\Service\Firestore\BatchGetDocumentsRequest;
use Google\Service\Firestore\BatchGetDocumentsResponse;
use Google\Service\Firestore\BatchWriteRequest;
use Google\Service\Firestore\BatchWriteResponse;
use Google\Service\Firestore\BeginTransactionRequest;
use Google\Service\Firestore\BeginTransactionResponse;
use Google\Service\Firestore\CommitRequest;
use Google\Service\Firestore\CommitResponse;
use Google\Service\Firestore\Document;
use Google\Service\Firestore\FirestoreEmpty;
use Google\Service\Firestore\ListCollectionIdsRequest;
use Google\Service\Firestore\ListCollectionIdsResponse;
use Google\Service\Firestore\ListDocumentsResponse;
use Google\Service\Firestore\ListenRequest;
use Google\Service\Firestore\ListenResponse;
use Google\Service\Firestore\PartitionQueryRequest;
use Google\Service\Firestore\PartitionQueryResponse;
use Google\Service\Firestore\RollbackRequest;
use Google\Service\Firestore\RunAggregationQueryRequest;
use Google\Service\Firestore\RunAggregationQueryResponse;
use Google\Service\Firestore\RunQueryRequest;
use Google\Service\Firestore\RunQueryResponse;
use Google\Service\Firestore\WriteRequest;
use Google\Service\Firestore\WriteResponse;

/**
 * The "documents" collection of methods.
 * Typical usage is:
 *  <code>
 *   $firestoreService = new Google\Service\Firestore(...);
 *   $documents = $firestoreService->projects_databases_documents;
 *  </code>
 */
class ProjectsDatabasesDocuments extends \Google\Service\Resource
{
  /**
   * Gets multiple documents. Documents returned by this method are not guaranteed
   * to be returned in the same order that they were requested.
   * (documents.batchGet)
   *
   * @param string $database Required. The database name. In the format:
   * `projects/{project_id}/databases/{database_id}`.
   * @param BatchGetDocumentsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return BatchGetDocumentsResponse
   * @throws \Google\Service\Exception
   */
  public function batchGet($database, BatchGetDocumentsRequest $postBody, $optParams = [])
  {
    $params = ['database' => $database, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('batchGet', [$params], BatchGetDocumentsResponse::class);
  }
  /**
   * Applies a batch of write operations. The BatchWrite method does not apply the
   * write operations atomically and can apply them out of order. Method does not
   * allow more than one write per document. Each write succeeds or fails
   * independently. See the BatchWriteResponse for the success status of each
   * write. If you require an atomically applied set of writes, use Commit
   * instead. (documents.batchWrite)
   *
   * @param string $database Required. The database name. In the format:
   * `projects/{project_id}/databases/{database_id}`.
   * @param BatchWriteRequest $postBody
   * @param array $optParams Optional parameters.
   * @return BatchWriteResponse
   * @throws \Google\Service\Exception
   */
  public function batchWrite($database, BatchWriteRequest $postBody, $optParams = [])
  {
    $params = ['database' => $database, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('batchWrite', [$params], BatchWriteResponse::class);
  }
  /**
   * Starts a new transaction. (documents.beginTransaction)
   *
   * @param string $database Required. The database name. In the format:
   * `projects/{project_id}/databases/{database_id}`.
   * @param BeginTransactionRequest $postBody
   * @param array $optParams Optional parameters.
   * @return BeginTransactionResponse
   * @throws \Google\Service\Exception
   */
  public function beginTransaction($database, BeginTransactionRequest $postBody, $optParams = [])
  {
    $params = ['database' => $database, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('beginTransaction', [$params], BeginTransactionResponse::class);
  }
  /**
   * Commits a transaction, while optionally updating documents.
   * (documents.commit)
   *
   * @param string $database Required. The database name. In the format:
   * `projects/{project_id}/databases/{database_id}`.
   * @param CommitRequest $postBody
   * @param array $optParams Optional parameters.
   * @return CommitResponse
   * @throws \Google\Service\Exception
   */
  public function commit($database, CommitRequest $postBody, $optParams = [])
  {
    $params = ['database' => $database, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('commit', [$params], CommitResponse::class);
  }
  /**
   * Creates a new document. (documents.createDocument)
   *
   * @param string $parent Required. The parent resource. For example:
   * `projects/{project_id}/databases/{database_id}/documents` or `projects/{proje
   * ct_id}/databases/{database_id}/documents/chatrooms/{chatroom_id}`
   * @param string $collectionId Required. The collection ID, relative to
   * `parent`, to list. For example: `chatrooms`.
   * @param Document $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string documentId The client-assigned document ID to use for this
   * document. Optional. If not specified, an ID will be assigned by the service.
   * @opt_param string mask.fieldPaths The list of field paths in the mask. See
   * Document.fields for a field path syntax reference.
   * @return Document
   * @throws \Google\Service\Exception
   */
  public function createDocument($parent, $collectionId, Document $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'collectionId' => $collectionId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('createDocument', [$params], Document::class);
  }
  /**
   * Deletes a document. (documents.delete)
   *
   * @param string $name Required. The resource name of the Document to delete. In
   * the format:
   * `projects/{project_id}/databases/{database_id}/documents/{document_path}`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool currentDocument.exists When set to `true`, the target
   * document must exist. When set to `false`, the target document must not exist.
   * @opt_param string currentDocument.updateTime When set, the target document
   * must exist and have been last updated at that time. Timestamp must be
   * microsecond aligned.
   * @return FirestoreEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], FirestoreEmpty::class);
  }
  /**
   * Gets a single document. (documents.get)
   *
   * @param string $name Required. The resource name of the Document to get. In
   * the format:
   * `projects/{project_id}/databases/{database_id}/documents/{document_path}`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string mask.fieldPaths The list of field paths in the mask. See
   * Document.fields for a field path syntax reference.
   * @opt_param string readTime Reads the version of the document at the given
   * time. This must be a microsecond precision timestamp within the past one
   * hour, or if Point-in-Time Recovery is enabled, can additionally be a whole
   * minute timestamp within the past 7 days.
   * @opt_param string transaction Reads the document in a transaction.
   * @return Document
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Document::class);
  }
  /**
   * Lists documents. (documents.listProjectsDatabasesDocuments)
   *
   * @param string $parent Required. The parent resource name. In the format:
   * `projects/{project_id}/databases/{database_id}/documents` or
   * `projects/{project_id}/databases/{database_id}/documents/{document_path}`.
   * For example: `projects/my-project/databases/my-database/documents` or
   * `projects/my-project/databases/my-database/documents/chatrooms/my-chatroom`
   * @param string $collectionId Optional. The collection ID, relative to
   * `parent`, to list. For example: `chatrooms` or `messages`. This is optional,
   * and when not provided, Firestore will list documents from all collections
   * under the provided `parent`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string mask.fieldPaths The list of field paths in the mask. See
   * Document.fields for a field path syntax reference.
   * @opt_param string orderBy Optional. The optional ordering of the documents to
   * return. For example: `priority desc, __name__ desc`. This mirrors the `ORDER
   * BY` used in Firestore queries but in a string representation. When absent,
   * documents are ordered based on `__name__ ASC`.
   * @opt_param int pageSize Optional. The maximum number of documents to return
   * in a single response. Firestore may return fewer than this value.
   * @opt_param string pageToken Optional. A page token, received from a previous
   * `ListDocuments` response. Provide this to retrieve the subsequent page. When
   * paginating, all other parameters (with the exception of `page_size`) must
   * match the values set in the request that generated the page token.
   * @opt_param string readTime Perform the read at the provided time. This must
   * be a microsecond precision timestamp within the past one hour, or if Point-
   * in-Time Recovery is enabled, can additionally be a whole minute timestamp
   * within the past 7 days.
   * @opt_param bool showMissing If the list should show missing documents. A
   * document is missing if it does not exist, but there are sub-documents nested
   * underneath it. When true, such missing documents will be returned with a key
   * but will not have fields, `create_time`, or `update_time` set. Requests with
   * `show_missing` may not specify `where` or `order_by`.
   * @opt_param string transaction Perform the read as part of an already active
   * transaction.
   * @return ListDocumentsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsDatabasesDocuments($parent, $collectionId, $optParams = [])
  {
    $params = ['parent' => $parent, 'collectionId' => $collectionId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListDocumentsResponse::class);
  }
  /**
   * Lists all the collection IDs underneath a document.
   * (documents.listCollectionIds)
   *
   * @param string $parent Required. The parent document. In the format:
   * `projects/{project_id}/databases/{database_id}/documents/{document_path}`.
   * For example: `projects/my-project/databases/my-
   * database/documents/chatrooms/my-chatroom`
   * @param ListCollectionIdsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return ListCollectionIdsResponse
   * @throws \Google\Service\Exception
   */
  public function listCollectionIds($parent, ListCollectionIdsRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('listCollectionIds', [$params], ListCollectionIdsResponse::class);
  }
  /**
   * Lists documents. (documents.listDocuments)
   *
   * @param string $parent Required. The parent resource name. In the format:
   * `projects/{project_id}/databases/{database_id}/documents` or
   * `projects/{project_id}/databases/{database_id}/documents/{document_path}`.
   * For example: `projects/my-project/databases/my-database/documents` or
   * `projects/my-project/databases/my-database/documents/chatrooms/my-chatroom`
   * @param string $collectionId Optional. The collection ID, relative to
   * `parent`, to list. For example: `chatrooms` or `messages`. This is optional,
   * and when not provided, Firestore will list documents from all collections
   * under the provided `parent`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string mask.fieldPaths The list of field paths in the mask. See
   * Document.fields for a field path syntax reference.
   * @opt_param string orderBy Optional. The optional ordering of the documents to
   * return. For example: `priority desc, __name__ desc`. This mirrors the `ORDER
   * BY` used in Firestore queries but in a string representation. When absent,
   * documents are ordered based on `__name__ ASC`.
   * @opt_param int pageSize Optional. The maximum number of documents to return
   * in a single response. Firestore may return fewer than this value.
   * @opt_param string pageToken Optional. A page token, received from a previous
   * `ListDocuments` response. Provide this to retrieve the subsequent page. When
   * paginating, all other parameters (with the exception of `page_size`) must
   * match the values set in the request that generated the page token.
   * @opt_param string readTime Perform the read at the provided time. This must
   * be a microsecond precision timestamp within the past one hour, or if Point-
   * in-Time Recovery is enabled, can additionally be a whole minute timestamp
   * within the past 7 days.
   * @opt_param bool showMissing If the list should show missing documents. A
   * document is missing if it does not exist, but there are sub-documents nested
   * underneath it. When true, such missing documents will be returned with a key
   * but will not have fields, `create_time`, or `update_time` set. Requests with
   * `show_missing` may not specify `where` or `order_by`.
   * @opt_param string transaction Perform the read as part of an already active
   * transaction.
   * @return ListDocumentsResponse
   * @throws \Google\Service\Exception
   */
  public function listDocuments($parent, $collectionId, $optParams = [])
  {
    $params = ['parent' => $parent, 'collectionId' => $collectionId];
    $params = array_merge($params, $optParams);
    return $this->call('listDocuments', [$params], ListDocumentsResponse::class);
  }
  /**
   * Listens to changes. This method is only available via gRPC or WebChannel (not
   * REST). (documents.listen)
   *
   * @param string $database Required. The database name. In the format:
   * `projects/{project_id}/databases/{database_id}`.
   * @param ListenRequest $postBody
   * @param array $optParams Optional parameters.
   * @return ListenResponse
   * @throws \Google\Service\Exception
   */
  public function listen($database, ListenRequest $postBody, $optParams = [])
  {
    $params = ['database' => $database, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('listen', [$params], ListenResponse::class);
  }
  /**
   * Partitions a query by returning partition cursors that can be used to run the
   * query in parallel. The returned partition cursors are split points that can
   * be used by RunQuery as starting/end points for the query results.
   * (documents.partitionQuery)
   *
   * @param string $parent Required. The parent resource name. In the format:
   * `projects/{project_id}/databases/{database_id}/documents`. Document resource
   * names are not supported; only database resource names can be specified.
   * @param PartitionQueryRequest $postBody
   * @param array $optParams Optional parameters.
   * @return PartitionQueryResponse
   * @throws \Google\Service\Exception
   */
  public function partitionQuery($parent, PartitionQueryRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('partitionQuery', [$params], PartitionQueryResponse::class);
  }
  /**
   * Updates or inserts a document. (documents.patch)
   *
   * @param string $name The resource name of the document, for example
   * `projects/{project_id}/databases/{database_id}/documents/{document_path}`.
   * @param Document $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool currentDocument.exists When set to `true`, the target
   * document must exist. When set to `false`, the target document must not exist.
   * @opt_param string currentDocument.updateTime When set, the target document
   * must exist and have been last updated at that time. Timestamp must be
   * microsecond aligned.
   * @opt_param string mask.fieldPaths The list of field paths in the mask. See
   * Document.fields for a field path syntax reference.
   * @opt_param string updateMask.fieldPaths The list of field paths in the mask.
   * See Document.fields for a field path syntax reference.
   * @return Document
   * @throws \Google\Service\Exception
   */
  public function patch($name, Document $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Document::class);
  }
  /**
   * Rolls back a transaction. (documents.rollback)
   *
   * @param string $database Required. The database name. In the format:
   * `projects/{project_id}/databases/{database_id}`.
   * @param RollbackRequest $postBody
   * @param array $optParams Optional parameters.
   * @return FirestoreEmpty
   * @throws \Google\Service\Exception
   */
  public function rollback($database, RollbackRequest $postBody, $optParams = [])
  {
    $params = ['database' => $database, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('rollback', [$params], FirestoreEmpty::class);
  }
  /**
   * Runs an aggregation query. Rather than producing Document results like
   * Firestore.RunQuery, this API allows running an aggregation to produce a
   * series of AggregationResult server-side. High-Level Example: ``` -- Return
   * the number of documents in table given a filter. SELECT COUNT(*) FROM (
   * SELECT * FROM k where a = true ); ``` (documents.runAggregationQuery)
   *
   * @param string $parent Required. The parent resource name. In the format:
   * `projects/{project_id}/databases/{database_id}/documents` or
   * `projects/{project_id}/databases/{database_id}/documents/{document_path}`.
   * For example: `projects/my-project/databases/my-database/documents` or
   * `projects/my-project/databases/my-database/documents/chatrooms/my-chatroom`
   * @param RunAggregationQueryRequest $postBody
   * @param array $optParams Optional parameters.
   * @return RunAggregationQueryResponse
   * @throws \Google\Service\Exception
   */
  public function runAggregationQuery($parent, RunAggregationQueryRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('runAggregationQuery', [$params], RunAggregationQueryResponse::class);
  }
  /**
   * Runs a query. (documents.runQuery)
   *
   * @param string $parent Required. The parent resource name. In the format:
   * `projects/{project_id}/databases/{database_id}/documents` or
   * `projects/{project_id}/databases/{database_id}/documents/{document_path}`.
   * For example: `projects/my-project/databases/my-database/documents` or
   * `projects/my-project/databases/my-database/documents/chatrooms/my-chatroom`
   * @param RunQueryRequest $postBody
   * @param array $optParams Optional parameters.
   * @return RunQueryResponse
   * @throws \Google\Service\Exception
   */
  public function runQuery($parent, RunQueryRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('runQuery', [$params], RunQueryResponse::class);
  }
  /**
   * Streams batches of document updates and deletes, in order. This method is
   * only available via gRPC or WebChannel (not REST). (documents.write)
   *
   * @param string $database Required. The database name. In the format:
   * `projects/{project_id}/databases/{database_id}`. This is only required in the
   * first message.
   * @param WriteRequest $postBody
   * @param array $optParams Optional parameters.
   * @return WriteResponse
   * @throws \Google\Service\Exception
   */
  public function write($database, WriteRequest $postBody, $optParams = [])
  {
    $params = ['database' => $database, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('write', [$params], WriteResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsDatabasesDocuments::class, 'Google_Service_Firestore_Resource_ProjectsDatabasesDocuments');
