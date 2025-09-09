<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container mt-5">
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h5 class="mb-0"><?=html_escape($row->title ?? '게시물 상세')?></h5>
			<small class="text-muted">
				작성자: <?=html_escape($row->writer ?? '알 수 없음');?> |
				작성일: <?=isset($row->reg_date) ? date('Y-m-d H:i', strtotime($row->reg_date)) : '';?>
			</small>
		</div>
		<div class="card-body" style="min-height: 200px;">
			<!-- nl2br() 함수는 줄바꿈 문자(\n)를 <br> 태그로 변환해줍니다. -->
			<p class="card-text"><?=nl2br(html_escape($row->content ?? '내용이 없습니다.'));?></p>
		</div>
		<?php // 첨부파일이 있는 경우에만 파일 섹션을 표시합니다. ?>
		<?php if (isset($row->file_idx) && !empty($row->file_idx)): ?>
			<div class="card-footer text-start">
				<div class="d-flex justify-content-between align-items-center">
					<div>
						<strong>첨부파일: </strong>
						<a href="/download/board/<?=base64_encode($row->stored_file_name);?>/<?=base64_encode($row->origin_file_name);?>">
							<i class="fas fa-download me-1"></i> <?=html_escape($row->origin_file_name);?>
						</a>
					</div>
					<?php if (isset($currentUserId) && $row->writer === $currentUserId): ?>
						<form id="fileDeleteForm" class="d-flex align-items-start gap-2">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
							<input type="hidden" name="fileIdx" value="<?=$row->file_idx;?>">
							<input type="hidden" name="boardIdx" value="<?=$row->idx;?>">
							<button type="button" id="deleteFileBtn" class="btn btn-outline-danger btn-sm">파일 삭제
							</button>
						</form>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="d-flex justify-content-end gap-2" style="margin-top: 10px;">
		<!-- 컨트롤러에서 전달받은 쿼리 스트링을 사용하여 목록 페이지로 돌아가는 링크를 생성합니다. -->
		<a href="/board?<?=$queryString ?? ''?>" class="btn btn-secondary">
			<i class="fas fa-list me-1"></i> 목록
		</a>
		<?php if (isset($currentUserId) && $row->writer === $currentUserId): ?>
			<a href="/board/edit/<?=$row->idx;?>?<?=$queryString ?? '';?>" class="btn btn-info">
				<i class="fas fa-edit me-1"></i> 수정
			</a>
			<a href="/board/delete/<?=$row->idx;?>?<?=$queryString ?? '';?>" class="btn btn-danger"
			   onclick="return confirm('정말로 이 게시물을 삭제하시겠습니까?');">
				<i class="fas fa-trash-alt me-1"></i> 삭제
			</a>
		<?php endif; ?>
	</div>
</div>
<script>
	$(document).ready(function () {
		// '파일 삭제' 버튼 클릭 이벤트
		$('#deleteFileBtn').on('click', function (e) {
			e.preventDefault(); // 기본 동작 방지

			if (!confirm('첨부파일을 정말로 삭제하시겠습니까?\n이 작업은 되돌릴 수 없습니다.')) {
				return;
			}

			const form = $('#fileDeleteForm');
			const postData = form.serialize(); // 폼 데이터를 직렬화합니다. (CSRF 포함)

			$.ajax({
				url: "<?= site_url('board/ajaxDeleteFile'); ?>",
				type: 'POST',
				data: postData,
				dataType: 'json',
				success: function (response) {
					alert(response.message); // 서버로부터 받은 메시지 표시
					if (response.success) {
						// 성공 시, 페이지를 새로고침하여 파일이 사라진 것을 확인합니다.
						window.location.reload();
					}
				},
				error: function (xhr, status, error) {
					console.error("AJAX Error: ", status, error);
					alert('파일 삭제 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.');
				}
			});
		});
	});
</script>
