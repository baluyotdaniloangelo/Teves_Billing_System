<div class="modal fade" id="viewReminderModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-4">

      <!-- HEADER -->
      <div class="modal-header text-white rounded-top-4" style="background: linear-gradient(135deg, #4e73df, #1cc88a);">
        <h5 class="modal-title fw-bold" id="view_title"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body">

        <!-- ICON + DATE -->
        <div class="d-flex align-items-center mb-3">
          <div class="me-3">
            <i class="bi bi-bell-fill fs-2 text-primary"></i>
          </div>
          <div>
            <small class="text-muted" id="view_date"></small>
          </div>
        </div>

        <!-- CONTENT -->
        <div class="p-3 rounded-3 bg-light border">
          <p id="view_content" style="white-space: pre-line;"></p>
        </div>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer justify-content-between">

        <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
          Close
        </button>
		<!--
        <button id="markAsDoneBtn" class="btn btn-success btn-sm">
          ✔ Mark as Done
        </button>
		-->
      </div>

    </div>
  </div>
</div>