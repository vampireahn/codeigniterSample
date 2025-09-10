<div class="row justify-content-center">
	<div class="col-md-8 col-lg-6">
		<h2 class="text-center mb-4">회원가입</h2>

		<?php if (validation_errors()): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo validation_errors(); ?>
			</div>
		<?php endif; ?>

		<form action="/member/registerProc" method="post" id="registerForm">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
			<input type="hidden" name="id_check_status" id="id_check_status" value="">

			<div class="mb-3">
				<label for="userId" class="form-label">아이디</label>
				<div class="input-group">
					<input type="text" class="form-control" name="userId" id="userId" value="<?php echo set_value('userId'); ?>" required>
					<button class="btn btn-outline-secondary" type="button" id="idCheckBtn">중복체크</button>
				</div>
				<div id="idCheckMessage" class="form-text"></div>
			</div>

			<div class="mb-3">
				<label for="userName" class="form-label">이름</label>
				<input type="text" class="form-control" id="userName" name="userName" value="<?php echo set_value('userName'); ?>" required>
			</div>

			<div class="mb-3">
				<label for="email" class="form-label">이메일</label>
				<input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>" required>
			</div>

			<div class="mb-3">
				<label for="phone" class="form-label">휴대전화</label>
				<input type="text" class="form-control" id="phone" name="phone" value="<?php echo set_value('phone'); ?>" placeholder="010-1234-5678">
			</div>

			<div class="mb-3">
				<label for="password" class="form-label">비밀번호</label>
				<input type="password" class="form-control" id="password" name="password" required>
			</div>

			<div class="mb-3">
				<label for="passwordConfirm" class="form-label">비밀번호 확인</label>
				<input type="password" class="form-control" id="passwordConfirm" name="passwordConfirm" required>
			</div>

			<div class="d-grid">
				<button type="submit" class="btn btn-primary btn-lg">가입</button>
			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function() {
		const idCheckBtn = $('#idCheckBtn');
		const idCheckStatus = $('#id_check_status');
		const idCheckMessage = $('#idCheckMessage');

		function performIdCheck() {
		const userId = $('#userId').val().trim();
		if (!userId) {
			alert('아이디를 입력해주세요.');
			return;
		}

		// 중복 체크를 시도하면, 일단 이전의 체크 완료 상태를 리셋합니다.
		idCheckStatus.val('');
		idCheckMessage.text('').removeClass('text-success text-danger');

		// 보낼 데이터 객체 생성
		const postData = {
			'userId': userId
		};
		// csrfTokenName은 footer.php에서 정의된 전역 변수를 사용합니다.
		postData[csrfTokenName] = $('input[name="' + csrfTokenName + '"]').val();

		$.ajax({
			url: "<?php echo site_url('member/ajax_id_check'); ?>",
			type: 'POST',
			data: postData,
			dataType: 'json',
			success: function (response) {
				if (response.status === 'success') {
					if (response.is_duplicate) {
						idCheckMessage.text('이미 사용 중인 아이디입니다.').addClass('text-danger');
					} else {
						idCheckMessage.text('사용 가능한 아이디입니다.').addClass('text-success');
						// 사용 가능한 아이디임이 확인되었을 때만, hidden 필드의 값을 'checked'로 설정합니다.
						idCheckStatus.val('checked');
					}
				} else {
					idCheckMessage.text(response.message || '오류가 발생했습니다. 다시 시도해주세요.').addClass('text-danger');
				}
			},
			error: function () {
				alert('서버와 통신 중 오류가 발생했습니다.');
			}
		});
	}

		idCheckBtn.on('click', performIdCheck);

		// '가입' 버튼 클릭 시, 폼 제출(submit) 이벤트를 가로채서 아이디 중복 체크 여부를 먼저 확인합니다.
		$('#registerForm').on('submit', function (event) {
			if (idCheckStatus.val() !== 'checked') {
				// 중복 체크가 완료되지 않았으면, 폼 제출을 막고 사용자에게 알립니다.
				alert('아이디 중복 체크를 완료해주세요.');
				event.preventDefault();
				return false;
			}
		});

		// 아이디 입력 필드의 내용이 변경될 때마다, 중복 체크 완료 상태를 리셋합니다.
		$('#userId').on('input', function () {
			idCheckStatus.val('');
			idCheckMessage.text('').removeClass('text-success text-danger');
		});
	});
</script>
