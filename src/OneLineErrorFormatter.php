<?php declare(strict_types = 1);

namespace Grifart\PhpstanOneLine;

use PHPStan\Command\AnalysisResult;
use PHPStan\Command\ErrorFormatter\ErrorFormatter;
use PHPStan\File\FileHelper;
use Symfony\Component\Console\Style\OutputStyle;

class OneLineErrorFormatter implements ErrorFormatter
{
	/** @var FileHelper */
	private $fileHelper;

	public function __construct(
		FileHelper $fileHelper
	)
	{
		$this->fileHelper = $fileHelper;
	}

	public function formatErrors(
		AnalysisResult $analysisResult,
		OutputStyle $style
	): int
	{
		if (!$analysisResult->hasErrors()) {
			$style->success('No errors');
			return 0;
		}

		foreach ($analysisResult->getNotFileSpecificErrors() as $notFileSpecificError) {
			$style->writeln(sprintf('<unknown location> %s', $notFileSpecificError));
		}

		foreach ($analysisResult->getFileSpecificErrors() as $fileSpecificError) {
			$filename = RelativePathHelper::getRelativePath(
				$this->fileHelper->getWorkingDirectory(),
				$this->fileHelper->normalizePath($fileSpecificError->getFile())
			);
			$style->writeln(
				sprintf(
					'%s:%d %s',
					$filename,
					$fileSpecificError->getLine() ?? '?',
					$fileSpecificError->getMessage()
				)
			);
		}

		$style->error(sprintf($analysisResult->getTotalErrorsCount() === 1 ? 'Found %d error' : 'Found %d errors', $analysisResult->getTotalErrorsCount()));
		return 1;
	}

}
