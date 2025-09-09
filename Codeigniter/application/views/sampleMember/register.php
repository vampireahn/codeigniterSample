<h2>회원가입</h2>
<?php // redirect를 사용하지 않으므로, flashdata 대신 validation_errors()로 오류를 직접 표시합니다. ?>
<?php echo validation_errors('<div style="color:red;">', '</div>'); ?>
<form action="/member/registerProc" method="post">
	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
	<!-- 아이디 중복 체크 완료 상태를 저장할 hidden 필드를 추가합니다. -->
	<input type="hidden" name="id_check_status" id="id_check_status" value="">
	<?php // set_value() 함수를 사용하여 이전 입력값을 복원합니다. ?>
	<div>
		<label>아이디</label>
		<input type="text" name="userId" id="userId" value="<?php echo set_value('userId'); ?>" required>
		<button type="button" onclick="idCheck()" style="background-color: orange; color: white;">중복체크</button>
	</div>
	<div>
		<label>이름</label>
		<input type="text" name="userName" value="<?php echo set_value('userName'); ?>" required>
	</div>
	<div>
		<label>이메일</label>
		<input type="email" name="email" value="<?php echo set_value('email'); ?>" required>
	</div>
	<div>
		<label>휴대전화</label>
		<input type="text" name="phone" value="<?php echo set_value('phone'); ?>" placeholder="010-1234-5678">
	</div>
	<div>
		<label>비밀번호</label>
		<input type="password" name="password" required>
	</div>
	<div>
		<label>비밀번호 확인</label>
		<input type="password" name="passwordConfirm" required>
	</div>
	<button type="submit">가입</button>
</form>
<script>
	// '가입' 버튼 클릭 시, 폼 제출(submit) 이벤트를 가로채서 아이디 중복 체크 여부를 먼저 확인합니다.
	$('form').on('submit', function (event) {
		const idCheckStatus = $('#id_check_status').val();
		if (idCheckStatus !== 'checked') {
			// 중복 체크가 완료되지 않았으면, 폼 제출을 막고 사용자에게 알립니다.
			alert('아이디 중복 체크를 완료해주세요.');
			event.preventDefault();
			return false;
		}
	});

	// 아이디 입력 필드의 내용이 변경될 때마다, 중복 체크 완료 상태를 리셋합니다.
	// 이렇게 하면 사용자가 아이디를 수정한 후에는 반드시 다시 중복 체크를 해야 합니다.
	$('#userId').on('input', function () {
		$('#id_check_status').val('');
	});

	function idCheck() {
		const userId = $('#userId').val().trim();
		if (!userId) {
			alert('아이디를 입력해주세요.');
			return;
		}

		// 중복 체크를 시도하면, 일단 이전의 체크 완료 상태를 리셋합니다.
		$('#id_check_status').val('');

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
						alert('이미 사용 중인 아이디입니다.');
					} else {
						alert('사용 가능한 아이디입니다.');
						// 사용 가능한 아이디임이 확인되었을 때만, hidden 필드의 값을 'checked'로 설정합니다.
						$('#id_check_status').val('checked');
					}
				} else {
					alert(response.message || '오류가 발생했습니다. 다시 시도해주세요.');
				}
			},
			error: function () {
				alert('서버와 통신 중 오류가 발생했습니다.');
			}
		});
	}
</script>
