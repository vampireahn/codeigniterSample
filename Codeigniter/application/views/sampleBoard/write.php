<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container mt-5">
	<?php
	// 수정 모드일 경우, 목록으로 돌아갈 때 필요한 쿼리 스트링을 form action URL에 추가합니다.
	$formActionUrl = isset($queryString) ? ($formAction . '?' . $queryString) : ($formAction ?? '');
	echo form_open_multipart($formActionUrl, ['id' => 'boardForm']);
	?>
	<div class="card">
		<div class="card-header">
			<h5 class="mb-0"><?= $pageTitle ?? '게시판' ?></h5>
		</div>
		<div class="card-body">
			<!-- 오류 메시지 표시 -->
			<?php if ($this->session->flashdata('error')): ?>
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<?= $this->session->flashdata('error'); ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			<?php endif; ?>

			<div class="mb-3">
				<label for="title" class="form-label">제목</label>
				<input type="text" class="form-control" id="title" name="title" value="<?= html_escape(set_value('title', $row->title ?? '')); ?>" required>
			</div>

			<div class="mb-3">
				<label for="writer" class="form-label">작성자</label>
				<input type="text" class="form-control" id="writer" name="writer" value="<?= html_escape($row->writer ?? get_user_id()); ?>" readonly>
			</div>

			<div class="mb-3">
				<label for="content" class="form-label">내용</label>
				<textarea class="form-control" id="content" name="content" rows="10" required><?= html_escape(set_value('content', $row->content ?? '')); ?></textarea>
			</div>

			<div class="mb-3">
				<label for="uploadFile" class="form-label">첨부파일</label>
				<input class="form-control" type="file" id="uploadFile" name="uploadFile">
				<?php // '수정' 모드이고 기존 파일이 있을 경우, 파일 정보와 삭제 체크박스를 표시합니다. ?>
				<?php if (isset($row->file_idx) && !empty($row->file_idx)): ?>
					<p class="form-text mt-2">
						<i class="fas fa-paperclip me-1"></i> 현재 파일: <?=html_escape($row->origin_file_name); ?>
						<small class="text-muted ms-2">(새 파일을 첨부하면 이 파일은 자동으로 삭제됩니다.)</small>
					</p>
				<?php endif; ?>
			</div>
		</div>
		<div class="card-footer text-end">
			<a href="<?= isset($row->idx) ? ('/board/view/' . $row->idx . '?' . ($queryString ?? '')) : '/board' ?>" class="btn btn-secondary">취소</a>
			<button type="submit" class="btn btn-primary"><?= isset($row->idx) ? '수정' : '등록' ?></button>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>
