<?php
namespace App\Validate;


class FilterWords
{
    protected $dict;//敏感词字典

    public function __construct()
    {
        $this->loadDataFormFile();
    }

    /**
     * 从文件中加载敏感词字典
     */
    protected function loadDataFormFile()
    {
        //此处可以修改为读文件，一般敏感词为文件形式，一行对应一个敏感词
        //如果经常调用的话，还可以通过缓存处理（redis、memcache）等等，此处不详细处理
        $arr = [
            '笨蛋',
            '笨蛋子',
            '傻瓜',
            '傻瓜蛋',
            '傻瓜子',
            '傻瓜蛋子',
            '傻笨蛋瓜子',
            '中国',
            '中国红',
        ];
        //将敏感词加入此次节点
        foreach ($arr as $value) {
            $this->addWords(trim($value));
        }
    }
    /**
     * 分割文本
     * @param $str
     * @return array[]|false|string[]
     */
    protected function splitStr($str)
    {
        //将字符串分割成组成它的字符
        // 其中/u 表示按unicode(utf-8)匹配（主要针对多字节比如汉字），否则默认按照ascii码容易出现乱码
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * 添加敏感字至节点
     * @param $words
     */
    protected function addWords($words)
    {
        //1.分割字典
        $wordArr = $this->splitStr($words);
        $curNode = &$this->dict;
        foreach ($wordArr as $char) {
            if (!isset($curNode)) {
                $curNode[$char] = [];
            }
            $curNode = &$curNode[$char];
        }
        //标记到达当前节点完整路径为"敏感词"
        $curNode['end'] = 1;
    }

    /**
     * 敏感词校验
     * @param $str ;需要校验的字符串
     * @param int $level ;屏蔽词校验等级 1-只要顺序包含都屏蔽；2-中间间隔skipDistance个字符就屏蔽；3-全词匹配即屏蔽
     * @param int $skipDistance ;允许敏感词跳过的最大距离，如笨aa蛋a傻瓜等等
     * @param bool $isReplace ;是否需要替换，不需要的话，返回是否有敏感词，否则返回被替换的字符串
     * @param string $replace ;替换字符
     * @return bool|string
     */
    public function filter($str, $level = 1, $skipDistance = 2, $isReplace = true, $replace = '*')
    {
        //允许跳过的最大距离
        if ($level == 1) {
            $maxDistance = strlen($str) + 1;
        } elseif ($level == 2) {
            $maxDistance = max($skipDistance, 0) + 1;
        } else {
            $maxDistance = 2;
        }
        $strArr = $this->splitStr($str);
        $strLength = count($strArr);
        $isSensitive = false;
        for ($i = 0; $i < $strLength; $i++) {
            //判断当前敏感字是否有存在对应节点
            $curChar = $strArr[$i];
            if (!isset($this->dict[$curChar])) {
                continue;
            }
            $isSensitive = true; //引用匹配到的敏感词节点
            $curNode = &$this->dict[$curChar];
            $dist = 0;
            $matchIndex = [$i]; //匹配后续字符串是否match剩余敏感词
            for ($j = $i + 1; $j < $strLength && $dist < $maxDistance; $j++) {
                if (!isset($curNode[$strArr[$j]])) {
                    $dist++; continue;
                }
                //如果匹配到的话，则把对应的字符所在位置存储起来，便于后续敏感词替换
                $matchIndex[] = $j;
                //继续引用
                $curNode = &$curNode[$strArr[$j]];
            }
            //判断是否已经到敏感词字典结尾，是的话，进行敏感词替换
            if (isset($curNode['end']) && $isReplace) {
                foreach ($matchIndex as $index) {
                    $strArr[$index] = $replace;
                }
                $i = max($matchIndex);
            }
        }
        if ($isReplace) {
            return implode('', $strArr);
        } else {
            return $isSensitive;
        }
    }
}
