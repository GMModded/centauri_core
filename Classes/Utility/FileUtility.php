<?php
namespace Centauri\Core\Utility;

class FileUtility {
    /**
     * Returns file reference(s) / object(s) for (multiple) file(s)
     * 
     * @param int|string $uid
     * @param string $tableName
     * @param string $fieldName
     * @param null|\TYPO3\CMS\Core\Resource\FileRepository $fileRepo
     * 
     * @return void
     */
    public static function findFilesBy($uid, $tableName, $fieldName, $fileRepo = null) {
        if(is_null($fileRepo)) {
            $fileRepo = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
        }

        return $fileRepo->findByRelation($tableName, $fieldName, $uid);
    }
}
