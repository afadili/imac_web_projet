<?php

namespace App\Http\Controllers;

use App\Statistics;

use App\Emoji;
use App\Hashtag;

use Illuminate\Http\Request;

// TODO : Documentation
class StatisticsController extends Controller
{
    public function getBatch($date)
    {
        // TODO or DELETE
    }

    // TODO: ADD MINMAX DATE
    public function getFromEmoji($code) {
        $idEmoji = Emoji::where('code', 'like', '%U+'.$code.'%')->get()->first()->id;
        $sql = '
            id IN (
                SELECT idStat FROM relations
                WHERE idEmoji = ? AND idHashtag IS NULL
            )';

        $res = Statistics::whereRaw($sql, [$idEmoji])->get();
        return response()->json($res);
    }

    // TODO: ADD MINMAX DATE
    public function getFromEmojiAndHashtag($hashtag,$code) {
        $idEmoji = Emoji::where('code', 'like', '%U+'.$code.'%')->get()->first()->id;
        $idHashtag = Hashtag::where('word', 'like', $hashtag)->get()->first()->id;

        $sql = '
            id IN (
                SELECT idStat FROM relations
                WHERE idEmoji = :emoji AND idHashtag = :hash
            )';

        $res = Statistics::whereRaw($sql, [':emoji' => $idEmoji, ':hash' => $idHashtag])->get();
        return response()->json($res);
    }
}