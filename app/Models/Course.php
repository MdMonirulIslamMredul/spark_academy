<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function courseCateory()
    {
        return $this->belongsTo(CourseCategory::class, 'catID', 'id');
    }

    /**
     * Ordered chapters belonging to this course.
     */
    public function chapters()
    {
        return $this->hasMany(CourseChapter::class, 'course_id', 'id')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }

    /**
     * All videos belonging to this course ordered by display order.
     */
    public function courseModules()
    {
        return $this->hasMany(CourseModule::class, 'course_id', 'id')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }

    /**
     * Synchronize the full chapter -> video tree for this course atomically.
     *
     * @param  array  $chaptersInput
     * @return void
     */
    public function syncChaptersAndVideos(array $chaptersInput)
    {
        $keptChapterIds = [];
        $keptVideoIds = [];

        foreach (array_values($chaptersInput) as $chapterIndex => $chapterData) {
            if (!is_array($chapterData)) {
                continue;
            }

            $chapterId = !empty($chapterData['id']) ? (int) $chapterData['id'] : null;
            $chapterPayload = [
                'course_id' => $this->id,
                'title' => trim((string) ($chapterData['title'] ?? '')),
                'description' => isset($chapterData['description']) && trim((string) $chapterData['description']) !== ''
                    ? trim((string) $chapterData['description'])
                    : null,
                'sort_order' => isset($chapterData['sort_order']) && $chapterData['sort_order'] !== ''
                    ? (int) $chapterData['sort_order']
                    : ($chapterIndex + 1),
            ];

            $chapter = null;
            if ($chapterId) {
                $chapter = CourseChapter::where('course_id', $this->id)
                    ->where('id', $chapterId)
                    ->first();
            }

            if ($chapter) {
                $chapter->update($chapterPayload);
            } else {
                $chapter = CourseChapter::create($chapterPayload);
            }

            $keptChapterIds[] = $chapter->id;

            $videosInput = isset($chapterData['videos']) && is_array($chapterData['videos'])
                ? array_values($chapterData['videos'])
                : [];

            foreach ($videosInput as $videoIndex => $videoData) {
                if (!is_array($videoData)) {
                    continue;
                }

                $videoId = !empty($videoData['id']) ? (int) $videoData['id'] : null;
                $videoPayload = [
                    'course_id' => $this->id,
                    'chapter_id' => $chapter->id,
                    'title' => trim((string) ($videoData['title'] ?? '')),
                    'link' => trim((string) ($videoData['link'] ?? '')),
                    'duration' => isset($videoData['duration']) && trim((string) $videoData['duration']) !== ''
                        ? trim((string) $videoData['duration'])
                        : null,
                    'free_paid' => ($videoData['free_paid'] ?? 'paid') === 'free' ? 'free' : 'paid',
                    'sort_order' => isset($videoData['sort_order']) && $videoData['sort_order'] !== ''
                        ? (int) $videoData['sort_order']
                        : ($videoIndex + 1),
                ];

                $video = null;
                if ($videoId) {
                    $video = CourseModule::where('course_id', $this->id)
                        ->where('id', $videoId)
                        ->first();
                }

                if ($video) {
                    $video->update($videoPayload);
                } else {
                    $video = CourseModule::create($videoPayload);
                }

                $keptVideoIds[] = $video->id;
            }
        }

        CourseModule::where('course_id', $this->id)
            ->when(!empty($keptVideoIds), function ($q) use ($keptVideoIds) {
                $q->whereNotIn('id', $keptVideoIds);
            })
            ->delete();

        CourseChapter::where('course_id', $this->id)
            ->when(!empty($keptChapterIds), function ($q) use ($keptChapterIds) {
                $q->whereNotIn('id', $keptChapterIds);
            })
            ->delete();
    }
}
