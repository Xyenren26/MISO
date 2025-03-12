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
              let ticket = data.ticket;
  
              // Set values dynamically
              document.getElementById('EndorsementControlNo').value = endorsement.control_no || '';
              document.getElementById('EndorsementEmployee').value = ticket.name || '';
              document.getElementById('EndorsementEmployeeID').value = ticket.employee_id || '';
              document.getElementById('EndorsementDepartment').value = endorsement.department || '';
              document.getElementById('endorsed_to').value = endorsement.endorsed_to || '';
              document.getElementById('endorsed_to_date').value = endorsement.endorsed_to_date || new Date().toISOString().split('T')[0];
              document.getElementById('endorsed_to_time').value = endorsement.endorsed_to_time || new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
              document.getElementById('endorsed_to_remarks').value = endorsement.endorsed_to_remarks || '';
              
              // Fetch and display Technical Support Name
              if (data.technical_support) {
                  document.getElementById('technical_Support_division').value =
                      `${data.technical_support.first_name} ${data.technical_support.last_name}`;
                  document.getElementById('remarkstechnical').value = data.technical_support.remarks || '';
              } else {
                  document.getElementById('technical_Support_division').value = 'Not Assigned';
              }
  
              document.getElementById('endorsed_by_date').value = endorsement.endorsed_by_date || new Date().toISOString().split('T')[0];
              document.getElementById('endorsed_by_time').value = endorsement.endorsed_by_time || new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
             
  
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
  

    fetch('/endorsement/details/' + ticket_id)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
                return;
            }

            function formatDate(dateString) {
                return dateString ? dateString.split('T')[0] : "";
            }

            // Populate endorsement fields
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
             // Display rating (if exists)
            if (data.rating) {
                document.getElementById('starRatingEndorsement').value = data.rating; // Numeric display
                displayStars(data.rating); // Star display
            } else {
                document.getElementById('starRatingEndorsement').innerHTML = "";
            }

            // Populate network checkboxes
            document.querySelectorAll('input[name="network[]"]').forEach(input => {
                input.checked = data.network.includes(input.value);
                let detailInput = input.nextElementSibling.nextElementSibling;
                if (detailInput) {
                    detailInput.value = data.network_details[input.value] || "";
                }
            });

            // Populate approval details
            document.getElementById("viewNotedBy").value = data.approval.name;
            document.getElementById("viewApproveDate").value = data.approval.approve_date;

           // Show or hide the waiting approval message
            if (data.approval.name === "Not Available" || data.approval.approve_date === "Not Available") {
                document.getElementById("waitingForApproval").style.display = "block";
                document.querySelector("button[onclick='downloadModalAsPDF()']").style.display = "none"; // Hide Download button
                document.getElementById("rating-containerEndorsement").style.display = "none";
            } else {
                document.getElementById("waitingForApproval").style.display = "none";
                document.querySelector("button[onclick='downloadModalAsPDF()']").style.display = "block"; // Show Download button
                document.getElementById("rating-containerEndorsement").style.display = "block";
            }




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
            // Populate approval details
            document.getElementById("viewNotedByTechnical").value = data.approval.name;
            document.getElementById("viewApproveDateTechnical").value = data.approval.approve_date;

           // Show or hide the waiting approval message
            if (data.approval.name === "Not Available" || data.approval.approve_date === "Not Available") {
                document.getElementById("waitingForApprovalTechnical").style.display = "block";
                document.getElementById("ButtonDownloadTechnical").style.display = "none"; // Hide Download button
                document.getElementById("rating-containerTechnical").style.display = "none";
            } else {
                document.getElementById("waitingForApproval").style.display = "none";
                document.getElementById("ButtonDownloadTechnical").style.display = "block"; // Show Download button
                document.getElementById("rating-containerTechnical").style.display = "block";
            }
             // Display rating (if exists)
            if (data.rating) {
                document.getElementById('starRatingTechnical').value = data.rating; // Numeric display
                displayStars(data.rating); // Star display
            } else {
                document.getElementById('starRatingTechnical').innerHTML = "";
            }
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
  document.getElementById("view-TR_id").value = report.TR_id;
  document.getElementById("view-date-time").value = formatDateTimeForInput(report.date_time);
  document.getElementById("view-inspected-date").value = formatDateTimeForInput(report.inspected_date);
  document.getElementById("viewTechnicalReportControlNo").value = report.control_no;
  document.getElementById("view-department").value = report.department;
  document.getElementById("view-enduser").value = report.enduser;
  document.getElementById("view-specification").value = report.specification;
  document.getElementById("view-problem").value = report.problem;
  document.getElementById("view-workdone").value = report.workdone;
  document.getElementById("view-findings").value = report.findings;
  document.getElementById("view-recommendation").value = report.recommendation;
  document.getElementById("view-inspected-by").value = report.inspected_by;

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
  document.getElementById('inspected-date').value = formattedDate;

  // Fetch department and end user from the tickets table
  fetch(`/get-ticket-details/${controlNo}`)
      .then(response => response.json())
      .then(data => {
          document.getElementById('TRcontrol_no').value = data.tr_id;
          document.getElementById('TechnicalReportDepartment').value = data.department;
          document.getElementById('enduser').value = data.enduser;
          document.getElementById('problemtech').value = data.concern;
          document.getElementById('workdonetech').value = data.remarks;
          document.getElementById('inspectedtech').value = data.technical_name;
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
            document.getElementById('viewFormNoService').value = data.form_no;
            document.getElementById('viewDepartment').value = data.department;
            document.getElementById('viewName').value = data.name;
            document.getElementById('viewEmployee_ID').value = data.employee_id;
            document.getElementById('viewStatusService').value = data.status;

            // Display rating (if exists)
            if (data.rating) {
                document.getElementById('starRatingPullOut').value = data.rating; // Numeric display
                displayStars(data.rating); // Star display
            } else {
                document.getElementById('starRatingPullOut').innerHTML = "";
            }

            // Only set QR code image if status is 'repaired'
            if (data.status.toLowerCase() === "repaired") {
                document.getElementById("qrCodeImage").src = `/generate-qr/${data.form_no}`;
                document.getElementById("qrCodeContainer").style.display = "block"; // Show QR code container
            } else{
                document.getElementById("qrCodeContainer").style.display = "none";
                document.getElementById("qrCodeImage").style.display = "none";
            }

            // Populate technical support
            document.getElementById('viewTechnicalSupport').value = data.technical_support;

            // Handle service_type radio buttons
            const serviceTypeId = `view_service_type_${data.service_type}`;
            if (document.getElementById(serviceTypeId)) {
                document.getElementById(serviceTypeId).checked = true;
            }

            // Populate condition radio buttons
            if (data.condition === 'working') {
                document.getElementById('view_condition_working').checked = true;
            } else if (data.condition === 'not-working') {
                document.getElementById('view_condition_not_working').checked = true;
            } else if (data.condition === 'needs-repair') {
                document.getElementById('view_condition_needs_repair').checked = true;
            }

            // Clear existing equipment descriptions
            const tableBody = document.getElementById("viewEquipmentTable");
            tableBody.innerHTML = ""; 

            if (data.equipment_descriptions && Array.isArray(data.equipment_descriptions)) {
                data.equipment_descriptions.forEach((equipment) => {
                    const row = document.createElement('tr');

                    // Brand Column
                    const brandCell = document.createElement('td');
                    const brandInput = document.createElement('input');
                    brandInput.classList.add('form-popup-input');
                    brandInput.type = 'text';
                    brandInput.value = equipment.brand || 'N/A';
                    brandInput.readOnly = true;
                    brandCell.appendChild(brandInput);
                    row.appendChild(brandCell);

                    // Device Column
                    const typeCell = document.createElement('td');
                    typeCell.textContent = equipment.equipment_type || 'Unknown';
                    row.appendChild(typeCell);

                    // Description Column
                    const descCell = document.createElement('td');
                    descCell.textContent = equipment.description || 'N/A';
                    row.appendChild(descCell);

                    // Remarks Column
                    const remarksCell = document.createElement('td');
                    const remarksInput = document.createElement('input');
                    remarksInput.classList.add('form-popup-input');
                    remarksInput.type = 'text';
                    remarksInput.value = equipment.remarks || 'N/A';
                    remarksInput.readOnly = true;
                    remarksCell.appendChild(remarksInput);
                    row.appendChild(remarksCell);

                    tableBody.appendChild(row);
                });
            }

            // Populate approval details
            document.getElementById("viewNotedByService").value = data.approval?.name || "Not Available";
            document.getElementById("viewApproveDateService").value = data.approval?.approve_date || "Not Available";

            // Show or hide approval waiting message
            if (data.approval?.name === "Not Available" || data.approval?.approve_date === "Not Available") {
                document.getElementById("waitingForApprovalService").style.display = "block";
                document.getElementById("ButtonService").style.display = "none"; // Hide Download button
                document.getElementById("rating-containerPullOut").style.display = "none";
            } else {
                document.getElementById("waitingForApprovalService").style.display = "none";
                document.getElementById("ButtonService").style.display = "block"; // Show Download button
                document.getElementById("rating-containerPullOut").style.display = "block"; // Corrected from "black"
            }

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
                  document.getElementById('pullOut').checked = true;
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

function approveTicket(controlNo) {
    if (confirm('Are you sure you want to approve this ticket?')) {
        fetch('/approve-ticket', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ticket_id: controlNo }) 
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                // Show the exact error message returned from Laravel
                alert('Approval issue: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while approving the ticket.');
        });
    }
}

function denyTicket(controlNo) {
    if (confirm('Are you sure you want to deny this ticket? This action will reset related records and set the status to in-progress.')) {
        fetch('/deny-ticket', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ticket_id: controlNo })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Denial issue: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while denying the ticket.');
        });
    }
}


let currentFormNo = '';

function openConfirmationModal(formNo) {
    currentFormNo = formNo;
    document.getElementById('modalFormNo').textContent = formNo;
    document.getElementById('confirmationModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('confirmationModal').style.display = 'none';
}

// Function to display stars
function displayStars(rating) {
    let starHtml = "";
    for (let i = 1; i <= 5; i++) {
        starHtml += i <= rating ? "⭐" : "☆"; // Filled star or empty star
    }
    document.getElementById('starRatingEndorsement').innerHTML = starHtml;
    document.getElementById('starRatingPullOut').innerHTML = starHtml;
    document.getElementById('starRatingTechnical').innerHTML = starHtml;
}