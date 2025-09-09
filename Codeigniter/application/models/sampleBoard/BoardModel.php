<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	class BoardModel extends MY_Model {
		private $tableName = "board";
		private $tableName2 = "board_file";

		/**
		 * 검색 조건에 맞는 게시물의 전체 개수를 반환합니다.
		 * @param string $searchField
		 * @param string $searchKeyword
		 * @return int
		 */
		public function getTotalCount($searchField = '', $searchKeyword = '') {
			$this->applySearchConditions($searchField, $searchKeyword)->db->from($this->tableName);
			
			return $this->db->count_all_results();
		}
		
		/**
		 * 검색 조건을 쿼리 빌더에 적용합니다.
		 * @param string $searchField
		 * @param string $searchKeyword
		 * @return BoardModel (메소드 체이닝을 위해)
		 */
		private function applySearchConditions($searchField, $searchKeyword) {
			if ($searchField && $searchKeyword) {
				$this->db->like($searchField, $searchKeyword);
			}
			return $this; // 메소드 체이닝을 위해 현재 객체를 반환합니다.
		}
		
		/**
		 * 모든 게시물을 가져옵니다.
		 * @return array
		 * @param int $limit
		 * @param int $offset
		 */
		public function findWithSearchAndPaging($limit, $offset, $searchField = '', $searchKeyword = '') {
			$this->applySearchConditions($searchField, $searchKeyword);
			$this->db->select('*');
			$this->db->from($this->tableName);
			$this->db->order_by('idx', 'desc');
			$this->db->limit($limit, $offset); // limit와 offset 적용
			
			$query = $this->db->get();
			
			return $query->result();
		}

		/**
		 * 검색 조건에 맞는 모든 게시물을 가져옵니다. (페이지네이션 없음)
		 * @param string $searchField
		 * @param string $searchKeyword
		 * @return array
		 */
		public function findAllWithSearch($searchField = '', $searchKeyword = '') {
			$this->applySearchConditions($searchField, $searchKeyword);
			$this->db->select('*');
			$this->db->from($this->tableName);
			$this->db->order_by('idx', 'desc');

			return $this->db->get()->result();
		}

		/**
		 * 특정 게시물과 첨부파일 정보를 함께 조회합니다.
		 * @param int $idx 게시물 고유 번호
		 * @return object|null
		 */
		public function getFindByIdx($idx) {
			$this->db->select('
				a.idx,
				a.title,
				a.writer,
				a.content,
				a.reg_date,
				b.idx as file_idx,
				b.origin_file_name,
				b.stored_file_name
			');
			$this->db->from($this->tableName . ' as a');
			$this->db->join($this->tableName2 . ' as b', 'a.idx = b.board_idx', 'left');
			$this->db->where('a.idx', $idx);
			
			$query = $this->db->get();
			return $query->row();
		}

		/**
		 * 특정 첨부파일의 정보를 조회합니다.
		 * @param int $idx 파일 고유 번호
		 * @return object|null
		 */
		public function getFileInfoByIdx($idx) {
			$this->db->select('origin_file_name, stored_file_name');
			$this->db->from($this->tableName2);
			$this->db->where('idx', $idx);
			
			$query = $this->db->get();
			return $query->row();
		}
		
		/**
		 * 새 게시글을 데이터베이스에 추가합니다.
		 * @param array $data 게시물 데이터
		 * @return int|bool 삽입된 게시물의 ID 또는 실패 시 false
		 */
		public function insertBoard($data) {
			$this->db->insert($this->tableName, $data);
			return $this->db->insert_id();
		}

		/**
		 * 새 첨부파일 정보를 데이터베이스에 추가합니다.
		 * @param array $data 파일 데이터
		 * @return void
		 */
		public function insertBoardFile($data) {
			$this->db->insert($this->tableName2, $data);
		}

		/**
		 * 특정 게시물의 정보를 업데이트합니다.
		 * @param int $idx 게시물 고유 번호
		 * @param array $data 업데이트할 데이터
		 * @return bool
		 */
		public function updateBoard($idx, $data) {
			$this->db->where('idx', $idx);
			return $this->db->update($this->tableName, $data);
		}

		/**
		 * 특정 첨부파일 정보를 데이터베이스에서 삭제합니다.
		 * @param int $idx 파일 고유 번호
		 * @return bool
		 */
		public function deleteBoardFile($idx) {
			$this->db->where('idx', $idx);
			return $this->db->delete($this->tableName2);
		}

		/**
		 * 특정 게시물을 데이터베이스에서 삭제합니다.
		 * @param int $idx 게시물 고유 번호
		 * @return bool
		 */
		public function deleteBoard($idx) {
			$this->db->where('idx', $idx);
			return $this->db->delete($this->tableName);
		}

		/**
		 * 여러 개의 게시물을 한 번에 데이터베이스에 추가합니다. (Batch Insert)
		 * @param array $data 2차원 배열 형태의 게시물 데이터
		 * @return int 삽입된 행의 개수
		 */
		public function insertBoardBatch($data) {
			$this->db->insert_batch($this->tableName, $data);
			return $this->db->affected_rows();
		}
	}
