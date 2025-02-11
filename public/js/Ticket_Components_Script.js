// Function to close the modal
function closeModal() {
    // Get the modal element
    var modal = document.getElementById('ticketModal');

    // Set the display style of the modal to 'none' to hide it
    modal.style.display = "none";
}

// You can also close the modal by clicking anywhere outside the modal content
window.onclick = function(event) {
    var modal = document.getElementById('ticketModal');
    if (event.target === modal) {
        closeModal();
    }
};


// Show Modal
function showAssistModal(ticketControlNo) {
  document.getElementById('assistModal').style.display = 'block';
  document.getElementById('ticketControlNo').value = ticketControlNo;
}

// Close Modal
function closeAssistModal() {
  document.getElementById('assistModal').style.display = 'none';
}

// Function to open the Remarks modal and set the control number
function openRemarksModal(controlNo) {
  const modal = document.getElementById("remarksModal");
  modal.style.display = "block";

  // Store the control number in the modal for submission
  modal.setAttribute("data-control-no", controlNo);
}

// Function to close the Remarks modal
function closeRemarksModal() {
  const modal = document.getElementById("remarksModal");
  modal.style.display = "none";

  // Clear input values
  document.getElementById("remarksInput").value = "";
  document.getElementById("statusDropdown").value = "completed";

  // Remove control number attribute
  modal.removeAttribute("data-control-no");
}

// Close modals when clicking outside of them
window.onclick = function(event) {
  const remarksModal = document.getElementById("remarksModal");
  if (event.target === remarksModal) {
      closeRemarksModal();
  }
};

function openEndorsementModal(ticket_id) {
  console.log('Opening endorsement modal for Ticket ID:', ticket_id);

  // Set hidden input value in the modal
  document.getElementById('ticket_id').value = ticket_id;

  // Fetch endorsement details from the backend
  fetch(`/endorsement-details/${ticket_id}`)
      .then(response => {
          if (!response.ok) {
              throw new Error(`Failed to fetch: ${response.status} ${response.statusText}`);
          }
          return response.json();
      })
      .then(data => {
          console.log('Received Endorsement Data:', data);

          if (data.endorsement) {
              let endorsement = data.endorsement;

              // Set values dynamically
              document.getElementById('EndorsementControlNo').value = endorsement.control_no || '';
              document.getElementById('EndorsementDepartment').value = endorsement.department || '';
              document.getElementById('endorsed_to').value = endorsement.endorsed_to || '';
              document.getElementById('endorsed_to_date').value = endorsement.endorsed_to_date || new Date().toISOString().split('T')[0];
              document.getElementById('endorsed_to_time').value = endorsement.endorsed_to_time || new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
              document.getElementById('endorsed_to_remarks').value = endorsement.endorsed_to_remarks || '';
              document.getElementById('technical_division').value = endorsement.endorsed_by || '';
              document.getElementById('date').value = endorsement.endorsed_by_date || new Date().toISOString().split('T')[0];
              document.getElementById('time').value = endorsement.endorsed_by_time || new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
              document.getElementById('remarks').value = endorsement.endorsed_by_remarks || '';

              // Handle checkboxes for network issues
              let networkCheckboxes = document.querySelectorAll('input[name="network[]"]');
              networkCheckboxes.forEach(checkbox => {
                  checkbox.checked = endorsement.network && endorsement.network.includes(checkbox.value);
              });

              // Handle checkboxes for user accounts
              let userAccountCheckboxes = document.querySelectorAll('input[name="user_account[]"]');
              userAccountCheckboxes.forEach(checkbox => {
                  checkbox.checked = endorsement.user_account && endorsement.user_account.includes(checkbox.value);
              });
          } else {
              alert('No endorsement details found.');
          }
      })
      .catch(error => {
          console.error('Error fetching endorsement details:', error.message);
          alert('Failed to fetch endorsement details. Please check the server.');
      });

  // Show modal
  document.getElementById('endorsementModal').style.display = 'block';
}


// Function to close the modal
function closeEndorsementModal() {
  document.getElementById('endorsementModal').style.display = 'none';
}

function openViewEndorsementModal(ticket_id) {
  // Fetch all details from the new route
  console.log('Ticket ID:', ticket_id);
  document.getElementById('ticket_id').value = ticket_id;
  fetch('/endorsement/details/' + ticket_id)
      .then(response => response.json())
      .then(data => {
          if (data.error) {
              console.error("Error:", data.error);
              return;
          }

          // Function to extract only the date part from a timestamp
          function formatDate(dateString) {
            return dateString ? dateString.split('T')[0] : "";
          }

          // Populate basic fields
          document.getElementById("ViewEndorsementcontrol_no").value = data.control_no || "";
          document.getElementById("ViewEndorsementdepartment").value = data.department || "";
          document.getElementById("endorsed_to").value = data.endorsed_to || "";
          document.getElementById("endorsed_to_date").value = formatDate(data.endorsed_to_date);
          document.getElementById("endorsed_to_time").value = data.endorsed_to_time || "";
          document.getElementById("endorsed_to_remarks").value = data.endorsed_to_remarks || "";
          document.getElementById("endorsed_by").value = data.endorsed_by || "";
          document.getElementById("endorsed_by_date").value = formatDate(data.endorsed_by_date);
          document.getElementById("endorsed_by_time").value = data.endorsed_by_time || "";
          document.getElementById("endorsed_by_remarks").value = data.endorsed_by_remarks || "";

          // Populate network checkboxes and input fields
          document.querySelectorAll('input[name="network[]"]').forEach(input => {
              input.checked = data.network.includes(input.value);
              let detailInput = input.nextElementSibling.nextElementSibling;
              if (detailInput) {
                  detailInput.value = data.network_details[input.value] || "";
              }
          });

          // Populate user account checkboxes and input fields
          document.querySelectorAll('input[name="user_account[]"]').forEach(input => {
              input.checked = data.user_account.includes(input.value);
              let detailInput = input.nextElementSibling.nextElementSibling;
              if (detailInput) {
                  detailInput.value = data.user_account_details[input.value] || "";
              }
          });

          // Show the modal
          document.getElementById("endorsementViewModal").style.display = "block";
      })
      .catch(error => console.error('Error fetching ticket details:', error));
}

// Close the modal
function closeEndorsementViewModal() {
  document.getElementById("endorsementViewModal").style.display = "none";
}

function checkTechnicalReport(controlNo) {
  fetch(`/technical-reports/check/${controlNo}`)
      .then(response => response.json())
      .then(data => {
          if (data.exists) {
              openTechnicalReportViewModal(data.report);
          } else {
              openTechnicalReportModal(controlNo);
          }
      })
      .catch(error => console.error('Error:', error));
}

function formatDateTimeForInput(datetime) {
  if (!datetime) return ""; // Handle null values
  const date = new Date(datetime);
  
  // Convert to "YYYY-MM-DDTHH:MM" format
  const formattedDate = date.toISOString().slice(0, 16);
  
  return formattedDate;
}

function openTechnicalReportViewModal(report) {
  document.getElementById("view-date-time").value = formatDateTimeForInput(report.date_time);
  document.getElementById("view-reported-date").value = formatDateTimeForInput(report.reported_date);
  document.getElementById("view-inspected-date").value = formatDateTimeForInput(report.inspected_date);
  document.getElementById("view-noted-date").value = formatDateTimeForInput(report.noted_date);

  document.getElementById("view-department").value = report.department;
  document.getElementById("view-enduser").value = report.enduser;
  document.getElementById("view-specification").value = report.specification;
  document.getElementById("view-problem").value = report.problem;
  document.getElementById("view-workdone").value = report.workdone;
  document.getElementById("view-findings").value = report.findings;
  document.getElementById("view-recommendation").value = report.recommendation;
  document.getElementById("view-reported-by").value = report.reported_by;
  document.getElementById("view-inspected-by").value = report.inspected_by;
  document.getElementById("view-noted-by").value = report.noted_by;

  document.getElementById("technicalReportViewModal").style.display = "block";
}

function closeTechnicalReportViewModal() {
  document.getElementById("technicalReportViewModal").style.display = "none";
}

function openTechnicalReportModal(controlNo) {
  document.getElementById('control_no').value = controlNo;

  // Set system-generated Date and Time
  let now = new Date();
  let formattedDate = now.toISOString().slice(0, 16);
  document.getElementById('date-time').value = formattedDate;

  // Fetch department and end user from the tickets table
  fetch(`/get-ticket-details/${controlNo}`)
      .then(response => response.json())
      .then(data => {
          document.getElementById('TechnicalReportDepartment').value = data.department;
          document.getElementById('enduser').value = data.enduser;
      })
      .catch(error => console.error('Error fetching ticket details:', error));

  document.getElementById('technicalModal').style.display = "block";
}

function closeTechnicalReportModal() {
  document.getElementById("technicalModal").style.display = "none";
}

function checkAndOpenPopup(ticketId) {
  fetch(`/check-service-request/${ticketId}`)
      .then(response => response.json())
      .then(data => {
          if (data.exists) {
              openViewModal(data.formNo);
          } else {
              openPopup(ticketId);
          }
      })
      .catch(error => console.error('Error:', error));
}

function openViewModal(formNo) {
  fetch(`/service-request/${formNo}`)
      .then(response => response.json())
      .then(data => {
          console.log(data); // Debugging: Log data to check the response

          // Populate general information
          document.getElementById('viewFormNo').textContent = data.form_no;
          document.getElementById('viewDepartment').value = data.department;
          document.getElementById('viewName').value = data.name;
          document.getElementById('viewEmployee_ID').value = data.employee_id;

          // Handle condition
          const condition = Array.isArray(data.condition) ? data.condition.join(', ') : data.condition || 'N/A';
          document.getElementById('viewCondition').textContent = condition;

          // Populate technical support
          document.getElementById('viewTechnicalSupport').value = data.technical_support;

          // Handle service_type and other fields as needed
          document.getElementById(`service_type_${data.service_type}`).checked = true;

          // Clear previous table rows
          const equipmentTable = document.getElementById('viewEquipmentTable');
          equipmentTable.innerHTML = ''; // Clear the table before adding new rows

          // Define equipment types and parts
          const equipmentTypes = [
              { type: 'System Unit', parts: ['motherboard', 'ram', 'hdd', 'accessories'], remarks: 'system_remarks' },
              { type: 'Monitor', parts: [], remarks: 'monitor_remarks' },
              { type: 'Laptop', parts: ['motherboard', 'ram', 'hdd', 'accessories'], remarks: 'laptop_remarks' },
              { type: 'Printer', parts: [], remarks: 'printer_remarks' },
              { type: 'UPS', parts: [], remarks: 'ups_remarks' }
          ];

          // Loop through each equipment type to generate and populate rows
          equipmentTypes.forEach((equipmentType) => {
              // Create a new table row
              const row = document.createElement('tr');

              // Add brand input field
              const brandCell = document.createElement('td');
              const brandInput = document.createElement('input');
              brandInput.classList.add('form-popup-input');
              brandInput.type = 'text';
              brandInput.name = `${equipmentType.type.toLowerCase().replace(' ', '_')}_brand`;
              brandInput.placeholder = 'Brand';
              row.appendChild(brandCell);
              brandCell.appendChild(brandInput);

              // Add equipment type description
              const typeCell = document.createElement('td');
              typeCell.textContent = equipmentType.type;
              row.appendChild(typeCell);

              // Add checkbox cells based on parts
              equipmentType.parts.forEach((part) => {
                  const partCell = document.createElement('td');
                  const checkbox = document.createElement('input');
                  checkbox.type = 'checkbox';
                  checkbox.name = `${equipmentType.type.toLowerCase().replace(' ', '_')}_${part}`;
                  partCell.appendChild(checkbox);
                  row.appendChild(partCell);
              });

              // Add remarks field
              const remarksCell = document.createElement('td');
              const remarksInput = document.createElement('input');
              remarksInput.classList.add('form-popup-input');
              remarksInput.type = 'text';
              remarksInput.name = `${equipmentType.type.toLowerCase().replace(' ', '_')}_remarks`;
              remarksInput.placeholder = 'Remarks';
              row.appendChild(remarksCell);
              remarksCell.appendChild(remarksInput);

              // Append row to the table
              equipmentTable.appendChild(row);

              // Fixing colspan for Remarks Column (Monitor, Printer, UPS)
              if (equipmentType.parts.length === 0) {
                  remarksCell.colSpan = 4; // Merge Remarks cell instead of Equipment Type
              }

              // Populate data for this equipment type
              const equipmentDescription = data.equipment_descriptions.find(description => description.equipment_type === equipmentType.type);
              if (equipmentDescription) {
                  // Set brand and remarks if available
                  brandInput.value = equipmentDescription.brand || '';
                  remarksInput.value = equipmentDescription.remarks || '';

                  // Handle parts (checkboxes)
                  equipmentType.parts.forEach((part) => {
                      const checkbox = row.querySelector(`[name="${equipmentType.type.toLowerCase().replace(' ', '_')}_${part}"]`);
                      if (checkbox) {
                          checkbox.checked = equipmentDescription.equipment_parts.some(p => p.toLowerCase() === part);
                      }
                  });
              }
          });

          // Display the modal
          document.getElementById('viewFormPopup').style.display = 'flex';
      })
      .catch(error => console.error('Error fetching service request details:', error));
}

function closePopup(id) {
  document.getElementById(id).style.display = 'none';
}

function openPopup(ticketControlNo = null) {
  var modal = document.getElementById("formPopup");

  if (ticketControlNo) {
      fetch(`/get-ticket-details/${ticketControlNo}`)
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  document.getElementById("ticket_id").value = ticketControlNo;
                  document.getElementById("name").value = data.name;
                  document.getElementById("employee_id").value = data.employee_id;
                  document.getElementById("Pulloutdepartment").value = data.department;
              }
          })
          .catch(error => console.error("Error fetching ticket details:", error));
  } else {
      document.getElementById("ticket_id").value = "";
  }

  modal.style.display = "block";
}

function closePopup(popupId) {
  document.getElementById(popupId).style.display = 'none';
}
