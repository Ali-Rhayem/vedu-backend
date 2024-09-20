<?php

namespace Tests\Feature;

use App\Models\Submission;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SubmissionControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_submissions()
    {
        Submission::factory(3)->create();

        $response = $this->getJson('/api/submission');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'assignment_id', 'student_id', 'submission_text', 'file_url', 'submitted_at']
            ]);
    }

    /** @test */
    public function it_can_create_a_submission()
    {
        Storage::fake('public');

        $assignment = Assignment::factory()->create();
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->postJson('/api/submission', [
            'assignment_id' => $assignment->id,
            'student_id' => $user->id,
            'submission_text' => 'My submission',
            'file' => $file,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'submission' => ['id', 'assignment_id', 'student_id', 'file_url', 'submitted_at'],
            ]);

        $this->assertTrue(Storage::disk('public')->exists('submissions/' . $file->hashName()));
        $this->assertDatabaseHas('submissions', ['submission_text' => 'My submission']);
    }

    /** @test */
    public function it_can_show_a_submission()
    {
        $submission = Submission::factory()->create();

        $response = $this->getJson("/api/submission/{$submission->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $submission->id,
                'assignment_id' => $submission->assignment_id,
            ]);
    }

    /** @test */
    public function it_can_update_a_submission()
    {
        Storage::fake('public');

        $assignment = Assignment::factory()->create(); 
        $student = User::factory()->create();           

        $submission = Submission::factory()->create([
            'assignment_id' => $assignment->id,         
            'student_id' => $student->id,              
            'submission_text' => 'Initial submission text',
            'file_url' => 'submissions/original.pdf',
        ]);

        $newFile = UploadedFile::fake()->create('new_submission.pdf', 100, 'application/pdf');

        $response = $this->putJson("/api/submission/{$submission->id}", [
            'assignment_id' => $submission->assignment_id,
            'student_id' => $submission->student_id,
            'submission_text' => 'Updated submission text',
            'file' => $newFile,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Submission updated successfully',
                'submission' => ['submission_text' => 'Updated submission text'],
            ]);

        $this->assertTrue(Storage::disk('public')->exists('submissions/' . $newFile->hashName()));

        $this->assertFalse(Storage::disk('public')->exists('submissions/original.pdf'));
    }



    /** @test */
    public function it_can_delete_a_submission()
    {
        $submission = Submission::factory()->create();

        $response = $this->deleteJson("/api/submission/{$submission->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Submission deleted successfully']);

        $this->assertDatabaseMissing('submissions', ['id' => $submission->id]);
    }

    /** @test */
    public function it_can_get_submissions_by_assignment()
    {
        $assignment = Assignment::factory()->create();
        Submission::factory(3)->create(['assignment_id' => $assignment->id]);

        $response = $this->getJson("/api/assignments/{$assignment->id}/submissions");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'assignment_id', 'student_id', 'submitted_at'],
            ]);
    }

    /** @test */
    public function it_can_grade_a_submission()
    {
        $assignment = Assignment::factory()->create(['grade' => 100]);
        $submission = Submission::factory()->create(['assignment_id' => $assignment->id]);

        $response = $this->postJson("/api/assignments/{$assignment->id}/submissions/{$submission->id}/grade", [
            'grade' => 90,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Grade saved successfully.',
                'submission' => ['grade' => 90],
            ]);

        $this->assertDatabaseHas('submissions', ['id' => $submission->id, 'grade' => 90]);
    }

    /** @test */
    public function it_can_download_a_file_attached_to_submission()
    {
        Storage::fake('public');

        $submission = Submission::factory()->create();
        $file = UploadedFile::fake()->create('submission.pdf', 100, 'application/pdf');
        $filePath = $file->store('submissions', 'public');
        $submission->update(['file_url' => $filePath]);

        $response = $this->get("/api/submissions/{$submission->id}/download");

        $fileName = basename($filePath);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/octet-stream')
            ->assertHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
