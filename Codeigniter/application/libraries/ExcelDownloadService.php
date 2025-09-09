<?php
defined('BASEPATH') or exit('No direct script access allowed');
	
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
	/**
 * Class ExcelDownloadService
 *
 * 데이터를 엑셀 파일로 변환하여 다운로드하는 공통 서비스입니다.
 */
class ExcelDownloadService
{
	/**
	 * 배열 데이터를 엑셀 파일로 생성하고 다운로드를 시작합니다.
	 *
	 * @param string $filename   - 다운로드될 파일의 이름 (e.g., '게시판_목록.xlsx')
	 * @param array  $headers    - 엑셀의 헤더 행 (e.g., ['번호', '제목', '작성자'])
	 * @param array  $dataRows   - 엑셀에 채워질 데이터 행들의 배열 (2차원 배열)
	 */
	public function download($filename, $headers, $dataRows)
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// 1. 헤더 설정
		$headerColumn = 'A';
		foreach ($headers as $header) {
			$sheet->setCellValue($headerColumn . '1', $header);
			$headerColumn++;
		}

		// 2. 데이터 채우기
		$rowNum = 2; // 데이터는 2번째 행부터 시작
		foreach ($dataRows as $row) {
			$dataColumn = 'A';
			foreach ($row as $cellData) {
				$sheet->setCellValue($dataColumn . $rowNum, $cellData);
				$dataColumn++;
			}
			$rowNum++;
		}

		// 3. 브라우저로 파일 다운로드
		// 파일명이 깨지지 않도록 UTF-8로 인코딩합니다.
		$encodedFilename = rawurlencode($filename);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $encodedFilename . '"; filename*=UTF-8\'\'' . $encodedFilename);
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		// php://output으로 출력하면 브라우저가 다운로드로 인식합니다.
		$writer->save('php://output');
		exit; // 다운로드 후 스크립트 실행을 중단합니다.
	}
}
