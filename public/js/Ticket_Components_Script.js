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

function openEndorsementModal(ticket_id) {
  console.log('Ticket ID:', ticket_id);

  fetch('/endorsement-details/' + ticket_id)
      .then(response => {
          if (!response.ok) {
              throw new Error(`Failed to fetch: ${response.status} ${response.statusText}`);
          }
          return response.json();
      })
      .then(data => {
          console.log('Endorsement Details:', data);

          if (data.endorsement) {
              document.getElementById('control_no').value = data.endorsement.control_no;
          } else {
              alert('No endorsement details found.');
          }
      })
      .catch(error => {
          console.error('Error:', error.message);
          alert('Failed to fetch endorsement details. Please check the server.');
      });

  document.getElementById('endorsementModal').style.display = 'block';
}

// Function to close the modal
function closeEndorsementModal() {
  document.getElementById('endorsementModal').style.display = 'none';
}



// Close modals when clicking outside of them
window.onclick = function(event) {
  const remarksModal = document.getElementById("remarksModal");
  if (event.target === remarksModal) {
      closeRemarksModal();
  }
};


