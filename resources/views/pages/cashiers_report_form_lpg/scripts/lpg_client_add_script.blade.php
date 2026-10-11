<script>
$(function () {
    const form = document.getElementById('ClientformNew');
    const modalElement = document.getElementById('CreateClientModal');
    const saveButton = document.getElementById('save-client');

    if (!form || !modalElement || !saveButton) return;

    function clearClientErrors() {
        form.classList.remove('was-validated');
        form.querySelectorAll('.is-invalid').forEach(function (field) {
            field.classList.remove('is-invalid');
        });
        form.querySelectorAll('.invalid-feedback').forEach(function (message) {
            message.textContent = '';
            message.classList.remove('d-block');
        });
    }

    function updateCompleteClientAddress() {
        const fieldIds = [
            'client_house_number', 'client_street', 'client_subdivision',
            'client_barangay', 'client_city', 'client_province', 'client_country'
        ];
        const address = fieldIds
            .map(function (id) {
                const field = document.getElementById(id);
                return field ? field.value.trim() : '';
            })
            .filter(Boolean)
            .join(', ');

        const addressField = document.getElementById('client_address');
        if (addressField) addressField.value = address;
    }

    function resetClientForm() {
        form.reset();
        const clientId = document.getElementById('client_id');
        if (clientId) clientId.value = '';
        clearClientErrors();
        updateCompleteClientAddress();
    }

    function validateClientForm() {
        form.classList.add('was-validated');
        const invalidField = Array.from(form.elements).find(function (field) {
            return field.willValidate && !field.checkValidity();
        });
        if (!invalidField) return true;

        const tabPane = invalidField.closest('.tab-pane');
        if (tabPane && !tabPane.classList.contains('show')) {
            const tabButton = modalElement.querySelector('[data-bs-target="#' + tabPane.id + '"]');
            if (tabButton && window.bootstrap && bootstrap.Tab) {
                bootstrap.Tab.getOrCreateInstance(tabButton).show();
            }
        }

        window.setTimeout(function () {
            invalidField.reportValidity();
        }, 100);
        return false;
    }

    function addClientToLpgLists(client) {
        ['lpgArClientNameList', 'lpgArSoClientNameList', 'lpgExpenseClientList']
            .forEach(function (listId) {
                const list = document.getElementById(listId);
                if (!list) return;

                let option = Array.from(list.options).find(function (item) {
                    return item.value === client.client_name;
                });
                if (!option) {
                    option = document.createElement('option');
                    list.appendChild(option);
                }

                option.value = client.client_name;
                option.label = client.client_name;
                option.dataset.id = client.client_id;
                option.dataset.accountNumber = client.client_account_number || '';
            });
    }

    function showClientValidationErrors(xhr) {
        const errors = xhr.responseJSON && xhr.responseJSON.errors
            ? xhr.responseJSON.errors
            : {};
        let firstError = '';

        Object.keys(errors).forEach(function (fieldName) {
            const message = Array.isArray(errors[fieldName]) ? errors[fieldName][0] : errors[fieldName];
            const input = form.querySelector('[name="' + fieldName + '"]');
            const feedback = document.getElementById(fieldName + '_error');

            if (input) input.classList.add('is-invalid');
            if (feedback) {
                feedback.textContent = message;
                feedback.classList.add('d-block');
            }
            if (!firstError) firstError = message;
        });

        if (typeof showValidationErrorModal === 'function') {
            showValidationErrorModal(firstError ||
                (xhr.responseJSON && xhr.responseJSON.message) || 'Unable to save client details.');
        }
    }

    function showClientSavedMessage(message) {
        const successText = document.getElementById('success_modal_message');
        if (successText) successText.textContent = message;

        if (typeof showSuccessModal === 'function') {
            showSuccessModal(message);
        } else {
            const successModal = document.getElementById('SuccessModal');
            if (successModal && window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(successModal).show();
            }
        }
    }

    $(modalElement).on('show.bs.modal', resetClientForm);
    $(form).on('input change', '#client_house_number, #client_street, #client_subdivision, #client_barangay, #client_city, #client_province, #client_country', updateCompleteClientAddress);

    $(saveButton).on('click', function (event) {
        event.preventDefault();
        clearClientErrors();
        updateCompleteClientAddress();
        if (!validateClientForm()) return;

        const formData = $(form).serializeArray();
        const salesAgentName = (document.getElementById('sales_agent_id') || {}).value || '';
        const agentList = document.getElementById('sales_agent_name');
        const salesAgentOption = agentList
            ? Array.from(agentList.options).find(function (option) {
                return option.value === salesAgentName;
            })
            : null;

        formData.push({ name: 'sales_agent_idx', value: salesAgentOption ? salesAgentOption.dataset.id : '' });
        formData.push({ name: '_token', value: "{{ csrf_token() }}" });

        const originalButtonHtml = saveButton.innerHTML;
        saveButton.disabled = true;
        saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

        $.ajax({
            url: "{{ route('create_client_post') }}",
            type: 'POST',
            data: $.param(formData),
            success: function (response) {
                const client = response.client;
                if (!client || !client.client_id || !client.client_name) {
                    showValidationErrorModal('The client was saved, but its account details were not returned.');
                    return;
                }

                addClientToLpgLists(client);
                resetClientForm();
                bootstrap.Modal.getOrCreateInstance(modalElement).hide();

                const accountNumber = client.client_account_number
                    ? ' Account No.: ' + client.client_account_number + '.'
                    : '';
                window.setTimeout(function () {
                    showClientSavedMessage(
                        (response.success || 'Client account created successfully.') + accountNumber
                    );
                }, 400);
            },
            error: showClientValidationErrors,
            complete: function () {
                saveButton.disabled = false;
                saveButton.innerHTML = originalButtonHtml;
            }
        });
    });
});
</script>
