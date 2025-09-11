<div class="row justify-content-center">
	<div class="col-md-6 col-lg-5">
		<h2 class="text-center mb-4"><?= $this->lang->line('로그인'); ?></h2>

		<?php
			// 1. 폼 유효성 검사 오류를 표시합니다.
			if (validation_errors()): ?>
				<div class="alert alert-danger" role="alert">
					<?=validation_errors();?>
				</div>
			<?php endif; ?>

		<?php
			// 2. 컨트롤러에서 전달된 로그인 실패 오류를 표시합니다.
			if (isset($error)): ?>
				<div class="alert alert-danger" role="alert">
					<?=html_escape($error);?>
				</div>
			<?php
			endif; ?>

		<?php
			// 3. 회원가입 성공 등 다른 페이지에서 전달된 일회성 메시지를 표시합니다.
			if ($msg = $this->session->flashdata('notice')): ?>
				<div class="alert alert-info" role="alert">
					<?=html_escape($msg);?>
				</div>
			<?php
			endif; ?>

		<!-- 언어 변경 폼 -->
		<?= form_open("/lang/switch", ['id' => 'langForm']); ?>
			<div class="mb-3">
				<label for="language" class="form-label"><?= $this->lang->line('언어'); ?></label>
				<select class="form-select" name="language" id="language" onchange="document.getElementById('langForm').submit();">
					<?php
						$current_lang = $this->session->userdata('site_lang') ?? 'korean';

						// constants.php에 정의된 LANGUAGES 상수를 사용하여 옵션을 생성합니다.
						foreach (LANGUAGES as $code => $name):
							$selected = ($current_lang === $code) ? 'selected' : '';
							echo "<option value=\"{$code}\" {$selected}>{$name}</option>";
						endforeach;
					?>
				</select>
			</div>
		<?= form_close(); ?>

		<?=form_open("/auth/loginProc");?>
		<div class="mb-3">
			<label for="userId" class="form-label"><?= $this->lang->line('아이디'); ?></label>
			<input type="text" class="form-control" id="userId" name="userId" value="<?=set_value('userId');?>" required>
		</div>
		<div class="mb-3">
			<label for="password" class="form-label"><?= $this->lang->line('비밀번호'); ?></label>
			<input type="password" class="form-control" id="password" name="password" required>
		</div>
		<div class="d-grid">
			<button type="submit" class="btn btn-primary btn-lg"><?= $this->lang->line('로그인'); ?></button>
		</div>
	</div>
</div>
