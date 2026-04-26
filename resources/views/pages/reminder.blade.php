@extends('layouts.layout')
@section('content')
<style>
#reminderTable thead th {
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

#reminderTable tbody tr:hover {
    background-color: #f8f9fc;
    transition: 0.2s ease;
}

#reminderTable td {
    vertical-align: middle;
}
</style>
<main id="main" class="main">
    <section class="section">

<div class="card border-0 shadow-sm rounded-4">

    <!-- HEADER -->
    <div class="card-header bg-white border-0 px-4 pt-4 pb-2">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-bell-fill text-primary me-2"></i> {{ $title }}
                </h5>
                <small class="text-muted">Manage your reminders efficiently</small>
            </div>

            <button class="btn btn-primary rounded-pill px-3 shadow-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#CreateReminderModal">
                <i class="bi bi-plus-circle me-1"></i> Add Reminder
            </button>

        </div>

    </div>

    <!-- BODY -->
    <div class="card-body px-4 pb-4">

        <div class="table-responsive">
            <table class="table align-middle table-hover display nowrap"
                   id="reminderTable" width="100%">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Reminder Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody></tbody>

            </table>
        </div>

    </div>

</div>

<!-- ================= CREATE MODAL ================= -->
<div class="modal fade" id="CreateReminderModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <!-- HEADER -->
            <div class="modal-header text-white rounded-top-4"
                 style="background: linear-gradient(135deg, #4e73df, #1cc88a);">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-bell-fill me-2"></i> Create Reminder
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body px-4 py-3">
                <form id="reminderFormNew">

                    <!-- TITLE -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="reminders_title"
                               class="form-control rounded-3"
                               placeholder="Enter reminder title..." required>
                    </div>

                    <!-- CONTENT -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Content</label>
                        <textarea name="reminders_content"
                                  class="form-control rounded-3"
                                  rows="3"
                                  placeholder="Write your reminder details..."></textarea>
                    </div>

                    <!-- DATE -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reminder Date</label>
                        <input type="datetime-local" name="reminder_date"
                               class="form-control rounded-3" required>
                    </div>

                    <!-- RECURRING SWITCH -->
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <label class="form-label fw-semibold mb-0">Recurring</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   id="is_recurring" name="is_recurring">
                        </div>
                    </div>

                    <!-- RECURRING OPTIONS -->
                    <div id="recurrence_fields" class="p-3 rounded-3 border bg-light" style="display:none;">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Repeat</label>
                            <select class="form-control rounded-3"
                                    name="recurrence_type" id="recurrence_type">
                                <option value="">-- Select --</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label fw-semibold">End Date</label>
                            <input type="datetime-local"
                                   class="form-control rounded-3"
                                   name="recurrence_end_date"
                                   id="recurrence_end_date">
                        </div>

                    </div>

                </form>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer border-0 px-4 pb-4">

                <button class="btn btn-light border rounded-pill px-4"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <button class="btn btn-success rounded-pill px-4 shadow-sm"
                        id="saveReminder">
                    <i class="bi bi-check-circle me-1"></i> Save Reminder
                </button>

            </div>

        </div>
    </div>
</div>

<!-- ================= UPDATE MODAL ================= -->
<div class="modal fade" id="UpdateReminderModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">

      <!-- HEADER -->
      <div class="modal-header text-white rounded-top-4"
           style="background: linear-gradient(135deg, #36b9cc, #4e73df);">
        <h5 class="modal-title fw-bold">
          <i class="bi bi-pencil-square me-2"></i> Update Reminder
        </h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body px-4 py-3">
        <form id="reminderFormEdit">

          <input type="hidden" id="update_reminder_id">

          <!-- TITLE -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Title</label>
            <input type="text" id="update_reminders_title"
                   class="form-control rounded-3"
                   placeholder="Update title..." required>
          </div>

          <!-- CONTENT -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Content</label>
            <textarea id="update_reminders_content"
                      class="form-control rounded-3"
                      rows="3"
                      placeholder="Update reminder details..."></textarea>
          </div>

          <!-- DATE -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Reminder Date</label>
            <input type="datetime-local"
                   id="update_reminder_date"
                   class="form-control rounded-3" required>
          </div>

          <!-- RECURRING SWITCH -->
          <div class="mb-3 d-flex justify-content-between align-items-center">
            <label class="form-label fw-semibold mb-0">Recurring</label>
            <div class="form-check form-switch">
              <input class="form-check-input"
                     type="checkbox"
                     id="update_is_recurring">
            </div>
          </div>

          <!-- RECURRING OPTIONS -->
          <div id="update_recurrence_fields"
               class="p-3 rounded-3 border bg-light"
               style="display:none;">

            <div class="mb-3">
              <label class="form-label fw-semibold">Repeat</label>
              <select class="form-control rounded-3"
                      id="update_recurrence_type">
                <option value="">-- Select --</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
              </select>
            </div>

            <div>
              <label class="form-label fw-semibold">End Date</label>
              <input type="datetime-local"
                     class="form-control rounded-3"
                     id="update_recurrence_end_date">
            </div>

          </div>

        </form>
      </div>

      <!-- FOOTER -->
      <div class="modal-footer border-0 px-4 pb-4 d-flex justify-content-between">

        <button class="btn btn-light border rounded-pill px-4"
                data-bs-dismiss="modal">
          Cancel
        </button>

        <button class="btn btn-primary rounded-pill px-4 shadow-sm"
                id="updateReminder">
          <i class="bi bi-check-circle me-1"></i> Update
        </button>

      </div>

    </div>
  </div>
</div>

<!-- ================= DELETE MODAL ================= -->
<div class="modal fade" id="DeleteReminderModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 text-center p-4">

      <!-- ICON -->
      <div class="mb-3">
        <i class="bi bi-exclamation-triangle-fill text-danger"
           style="font-size: 3rem;"></i>
      </div>

      <!-- TITLE -->
      <h5 class="fw-bold mb-2">Delete Reminder</h5>

      <!-- MESSAGE -->
      <p class="text-muted mb-3">
        Are you sure you want to delete this reminder?
      </p>

      <div class="bg-light rounded-3 p-2 mb-3">
        <strong id="delete_reminder_title"></strong>
      </div>

      <!-- ACTIONS -->
      <div class="d-flex justify-content-center gap-2">

        <button class="btn btn-outline-secondary rounded-pill px-4"
                data-bs-dismiss="modal">
          Cancel
        </button>

        <button class="btn btn-danger rounded-pill px-4"
                id="confirmDeleteReminder">
          <i class="bi bi-trash me-1"></i> Delete
        </button>

      </div>

    </div>
  </div>
</div>

    </section>
</main>

@endsection