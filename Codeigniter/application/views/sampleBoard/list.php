<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container mt-5">
	<!-- 화면 중앙 로딩 오버레이 -->
	<div id="loading-overlay" class="d-none">
		<div class="spinner-border text-primary" role="status">
			<span class="visually-hidden">Loading...</span>
		</div>
	</div>
	<style>
		#loading-overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			display: flex;
			justify-content: center;
			align-items: center;
			z-index: 9999; /* 다른 요소들 위에 표시되도록 z-index 설정 */
		}
		#loading-overlay .spinner-border {
			width: 3rem;
			height: 3rem;
		}
	</style>
	<div class="card">
		<!-- 성공 또는 오류 메시지 표시 -->
		<?php if ($this->session->flashdata('notice')): ?>
			<div class="alert alert-success alert-dismissible fade show m-3" role="alert">
				<?= $this->session->flashdata('notice'); ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('error')): ?>
			<div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
				<?= $this->session->flashdata('error'); ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php endif; ?>

		<div class="card-header d-flex justify-content-between align-items-center">
			<h5 class="mb-0">게시판</h5>
			<div class="d-flex align-items-center gap-2">
				<!-- 엑셀 일괄 등록 폼 -->
				<form action="/board/excelUploadProc" method="post" enctype="multipart/form-data" id="excelUploadForm" class="d-flex align-items-center gap-2">
					<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
					<a href="/board/excelUploadFormDownload" class="btn btn-info btn-sm">
						<i class="fas fa-file-download me-1"></i> 양식 다운로드
					</a>
					<label for="excelFile" class="btn btn-warning btn-sm mb-0">
						<i class="fas fa-file-upload me-1"></i> 엑셀 선택
					</label>
					<input type="file" name="excelFile" id="excelFile" class="d-none" accept=".xlsx, .xls">
					<span id="excelFileName" class="text-muted small"></span>
				</form>

				<div class="vr"></div>

				<div class="d-flex gap-2">
					<!-- 현재 검색 조건을 유지한 채 엑셀 다운로드 링크로 연결합니다. -->
					<a href="/board/excelDownload?<?= $queryString ?? '' ?>" class="btn btn-success btn-sm">
						<i class="fas fa-file-excel me-1"></i> 엑셀 다운로드
					</a>
					<a href="/board/write" class="btn btn-primary btn-sm">
						<i class="fas fa-pen me-1"></i> 글쓰기
					</a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<div>
					<?php if (isset($searchKeyword) && !empty($searchKeyword)): ?>
						<small>'<strong class="text-danger"><?= html_escape($searchKeyword) ?></strong>'(으)로 검색된 결과 총 <strong class="text-primary"><?= $totalCount ?? 0 ?></strong>개</small>
					<?php else: ?>
						<small>총 <strong class="text-primary"><?= $totalCount ?? 0 ?></strong>개의 게시물이 있습니다.</small>
					<?php endif; ?>
				</div>
			</div>
			<table class="table table-hover">
				<thead class="table-light">
				<tr>
					<th scope="col" class="text-center d-none d-md-table-cell" style="width: 10%;">번호</th>
					<th scope="col">제목</th>
					<th scope="col" class="text-center" style="width: 15%;">작성자</th>
					<th scope="col" class="text-center d-none d-md-table-cell" style="width: 20%;">작성일</th>
				</tr>
				</thead>
				<tbody>
				<?php if (isset($list) && !empty($list)) : ?>
					<?php foreach ($list as $key => $item) : ?>
						<tr>
							<!-- 게시물 번호를 '전체 개수 - 현재 순번'으로 계산하여 표시합니다. -->
							<?php $number = ($totalCount ?? 0) - (($offset ?? 0) + $key); ?>
							<td class="text-center d-none d-md-table-cell"><?= $number; ?></td>
							<td>
								<a href="/board/view/<?= html_escape($item->idx); ?>?<?= $queryString ?? '' ?>" class="text-decoration-none text-dark">
									<?= html_escape($item->title); ?>
								</a>
							</td>
							<td class="text-center"><?= html_escape($item->writer); ?></td>
							<td class="text-center d-none d-md-table-cell"><?= html_escape(date('Y-m-d', strtotime($item->reg_date))); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="4" class="text-center py-5">게시물이 없습니다.</td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>

			<!-- 검색 폼 -->
			<div class="d-flex justify-content-center mt-4">
				<form action="/board" method="get" class="d-flex gap-1">
					<select name="searchType" class="form-select form-select-sm" style="width: 120px;">
						<option value="title" <?= set_select('searchType', 'title', ($searchType === 'title')); ?>>제목</option>
						<option value="content" <?= set_select('searchType', 'content', ($searchType === 'content')); ?>>내용</option>
						<option value="writer" <?= set_select('searchType', 'writer', ($searchType === 'writer')); ?>>작성자</option>
					</select>
					<input type="text" name="searchKeyword" class="form-control form-control-sm" placeholder="검색어 입력" value="<?= html_escape($searchKeyword ?? ''); ?>" style="width: 200px;">
					<button type="submit" class="btn btn-secondary btn-sm">
						<i class="fas fa-search"></i>
					</button>
					<?php if (isset($searchKeyword) && !empty($searchKeyword)): ?>
						<a href="/board" class="btn btn-outline-secondary btn-sm">
							<i class="fas fa-redo"></i>
						</a>
					<?php endif; ?>
				</form>
			</div>

			<!-- 페이징 링크 출력 -->
			<?php if (isset($paginationLinks) && !empty($paginationLinks)): ?>
				<div class="d-flex justify-content-center mt-4">
					<?= $paginationLinks; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		// '엑셀 선택'으로 열리는 파일 입력(input)의 변경 이벤트를 감지합니다.
		$('#excelFile').on('change', function() {
			const form = $('#excelUploadForm');
			const fileNameSpan = $('#excelFileName');
			const fileInput = this;

			if (fileInput.files.length > 0) {
				const fileName = fileInput.files[0].name;
				fileNameSpan.text(fileName); // 선택된 파일명을 표시합니다.

				// 사용자에게 업로드 여부를 확인합니다.
				if (confirm(fileName + ' 파일을 업로드하여 게시물을 일괄 등록하시겠습니까?')) {
					// '확인'을 누르면 화면 중앙에 로딩 오버레이를 표시하고 폼을 제출합니다.
					$('#loading-overlay').removeClass('d-none');
					form.submit();
				} else {
					// '취소'를 누르면 파일 선택을 초기화합니다.
					fileInput.value = '';
					fileNameSpan.text('');
				}
			}
		});
	});
</script>
