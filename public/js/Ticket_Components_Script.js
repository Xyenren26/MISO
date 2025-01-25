// Function to show ticket details in the modal
function showTicketDetails(controlNo) {
  console.log('Control Number:', controlNo); // Debugging: Check if the control_no is being passed correctly
  
  fetch('/ticket-details/' + controlNo) // Assuming this endpoint fetches ticket details by control_no
      .then(response => response.json())
      .then(data => {
          console.log('Ticket Details:', data); // Ensure the correct ticket data is received
  
          // Populate modal with ticket data
          document.getElementById('ticketControlNumber').innerText = data.ticket.control_no;
          document.getElementById('ticketFirstName').innerText = data.ticket.name;
          document.getElementById('ticketDepartment').innerText = data.ticket.department;
          document.getElementById('ticketConcern').innerText = data.ticket.concern;
          document.getElementById('ticketPriority').innerText = data.ticket.priority;
          document.getElementById('ticketEmployeeId').innerText = data.ticket.employee_id;
          document.getElementById('ticketTechnicalSupport').innerText = data.ticket.technical_support_name;
          document.getElementById('ticketTimeIn').innerText = data.ticket.time_in;
  
          // Populate the Support History Section
          const historyList = document.getElementById('supportHistoryList');
          historyList.innerHTML = ''; // Clear the list before appending new items
          
          // Check if we have ticket history data
          if (data.ticketHistory) {
              const historyItem = document.createElement('li');
              historyItem.innerHTML = `
                  <strong>Previous Support:</strong> ${data.ticketHistory.previous_technical_support_name} 
                  <strong>New Support:</strong> ${data.ticketHistory.new_technical_support_name} 
                  <strong>Changed At:</strong> ${data.ticketHistory.changed_at}
              `;
              historyList.appendChild(historyItem);
          } else {
              const noHistoryItem = document.createElement('li');
              noHistoryItem.innerText = 'No support history available.';
              historyList.appendChild(noHistoryItem);
          }
  
          // Show modal
          document.getElementById('ticketModal').style.display = "block";
      })
      .catch(error => {
          console.error('Error fetching ticket details:', error);
      });
}


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


const employeeIdInput = document.getElementById('employeeId');
const errorMessage = document.getElementById('error-message');

employeeIdInput.addEventListener('input', function () {
  // Remove any non-numeric characters and limit to 7 digits
  this.value = this.value.replace(/\D/g, '').slice(0, 7);

  // Check if the input length is exactly 7 digits
  if (this.value.length === 7) {
    errorMessage.style.display = 'none'; // Hide error message
    this.setCustomValidity(''); // Clear validation message
  } else {
    errorMessage.style.display = 'inline'; // Show error message
    this.setCustomValidity('Invalid Employee ID'); // Set validation message
  }
});
 document.getElementById('otherConcernCheckbox').addEventListener('change', function() {
  const otherContainer = document.getElementById('otherConcernContainer');
  if (this.checked) {
      otherContainer.style.display = 'block';
  } else {
      otherContainer.style.display = 'none';
  }
});
function updateSelectedConcerns() {
  const checkboxes = document.querySelectorAll('.issue-dropdown-content input[type="checkbox"]:checked');
  const selectedConcerns = Array.from(checkboxes)
      .filter(checkbox => checkbox.value !== 'Other') // Exclude "Other"
      .map(checkbox => checkbox.value);
  const otherInput = document.getElementById('otherConcern').value.trim();

  // Add "Other" input if it's filled
  if (document.getElementById('otherIssueCheckbox').checked && otherInput) {
      selectedConcerns.push(otherInput);
  }

  // Update the button text
  const dropbtn = document.getElementById('selectedConcerns');
  dropbtn.textContent = selectedConcerns.length > 0 ? selectedConcerns.join(', ') : 'Select Concern';
}

function toggleOtherInput() {
  const otherInputContainer = document.getElementById('otherConcernContainer');
  const otherCheckbox = document.getElementById('otherIssueCheckbox');

  otherInputContainer.style.display = otherCheckbox.checked ? 'block' : 'none';
}



  // Check if all required fields are filled before submitting the form
  function validateForm() {
      var firstName = document.getElementById('first-name').value;
      var lastName = document.getElementById('last-name').value;
      var department = document.getElementById('department').value;
      var concern = document.getElementById('concern').value;
      var category = document.getElementById('category').value;
      var employeeId = document.getElementById('employeeId').value;
      var technicalSupport = document.getElementById('technicalSupport').value;
      var errorMessage = document.getElementById('errorMessage');

      // Check if all required fields are filled
      if (!firstName || !lastName || !department || !concern || !category || !employeeId || !technicalSupport) {
          errorMessage.style.display = 'block'; // Show error message
          return false; // Prevent form submission
      }

      // If "Other" concern is selected, ensure "Specify" field is filled
      if (concern === 'other' && !document.getElementById('otherConcern').value) {
          errorMessage.style.display = 'block'; // Show error message
          return false; // Prevent form submission
      }

      errorMessage.style.display = 'none'; // Hide error message
      return true; // Allow form submission
  }

  // Attach the validateForm function to the submit button
  document.querySelector('.submit-btn').addEventListener('click', function(event) {
      if (!validateForm()) {
          event.preventDefault(); // Prevent form submission if validation fails
      } else {
          submitForm(); // Submit form if validation passes
      }
  });

  // Submit the form
  function submitForm() {
      document.getElementById('ticketForm').submit();
  }

  function printModal() {
    const modalContent = document.querySelector('#ticketModal .ticket-modal-content');
    const originalContent = document.body.innerHTML;
  
    // Temporarily hide the navigation, sidebar, and modal buttons
    const nav = document.querySelector('.navbar');
    const sidebar = document.querySelector('.sidebar');
    const header = document.querySelector('.head');
    const closeModalButton = modalContent.querySelector('.close-modal'); // Target close button inside modal
    const printModalButton = modalContent.querySelector('.print-modal'); // Target print button inside modal
    
    if (nav) nav.style.display = 'none';
    if (sidebar) sidebar.style.display = 'none';
    if (header) {
        header.style.marginTop = '70px';
    }
    if (closeModalButton) closeModalButton.style.display = 'none';
    if (printModalButton) printModalButton.style.display = 'none';
  
    // Get the HTML content of the modal (exclude close and print buttons)
    const printContent = modalContent.innerHTML;
  
    // Add CSS to control print layout and page breaks
    const style = `
      <style>
        @page {
          size: A4;
          margin: 0;
        }
        body {
          margin: 0;
          padding: 0;
        }
        .ticket-modal-content {
          width: 100%;
          height: auto;
          overflow: hidden;
          page-break-before: always;
        }
        .ticket-modal-content * {
          font-size: 12px; /* Adjust size as needed */
          word-wrap: break-word;
        }
      </style>
    `;
  
    // Insert print styles into the document
    const head = document.querySelector('head');
    const styleTag = document.createElement('style');
    styleTag.innerHTML = style;
    head.appendChild(styleTag);
  
    // Print the content
    window.print();

    // Restore original content after printing
    document.body.innerHTML = originalContent;

    // Reload the page to restore JavaScript functionality
    location.reload();
}

  


// Show Modal
function showAssistModal(ticketControlNo) {
  document.getElementById('assistModal').style.display = 'block';
  document.getElementById('ticketControlNo').value = ticketControlNo;
}

// Close Modal
function closeAssistModal() {
  document.getElementById('assistModal').style.display = 'none';
}

async function submitAssist() {
  const ticketControlNo = document.getElementById('ticketControlNo').value;
  const technicalSupport = document.getElementById('technicalSupport').value;
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');  // Get the CSRF token

  if (!ticketControlNo || !technicalSupport) {
    alert("Please select a technical support and ensure the ticket control number is correct.");
    return;
  }

  try {
    const response = await fetch('/api/pass-ticket', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,  // Include CSRF token in the header
      },
      body: JSON.stringify({
        ticket_control_no: ticketControlNo,
        new_technical_support: technicalSupport,
      }),
    });

    if (response.ok) {
      alert('Ticket successfully passed!');
      closeAssistModal();
    } else {
      const errorData = await response.json();
      console.error("Error response:", errorData);
      alert('Error passing ticket: ' + errorData.error || 'Unknown error');
    }
  } catch (error) {
    console.error("Request failed:", error);
    alert('Request failed: ' + error.message);
  }
}

// Function to open the modal and set the control number
function openRemarksModal(controlNo) {
  const modal = document.getElementById("remarksModal");
  modal.style.display = "block";

  // Store the control number in the modal for submission
  modal.setAttribute("data-control-no", controlNo);
}

// Function to close the modal
function closeRemarksModal() {
  const modal = document.getElementById("remarksModal");
  modal.style.display = "none";

  // Clear input values
  document.getElementById("remarksInput").value = "";
  document.getElementById("statusDropdown").value = "completed";

  // Remove control number attribute
  modal.removeAttribute("data-control-no");
}

// Function to save remarks and status
function saveRemarksAndStatus() {
  const modal = document.getElementById("remarksModal");
  const controlNo = modal.getAttribute("data-control-no");
  const remarks = document.getElementById("remarksInput").value;
  const status = document.getElementById("statusDropdown").value;

  if (!remarks.trim()) {
      alert("Please enter remarks before saving.");
      return;
  }

  // Prepare the data for the AJAX request
  const requestData = {
      control_no: controlNo,
      remarks: remarks,
      status: status
  };

  // Send the request via Fetch API
  fetch("/tickets/update-remarks", {
      method: "POST",
      headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
      },
      body: JSON.stringify(requestData)
  })
  .then(response => {
      if (!response.ok) {
          throw new Error("Failed to update the ticket.");
      }
      return response.json();
  })
  .then(data => {
      alert(data.message);
      closeRemarksModal();

      // Optionally, you can refresh the ticket list or update the UI here
  })
  .catch(error => {
      alert(`Error: ${error.message}`);
  });
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
  const modal = document.getElementById("remarksModal");
  if (event.target === modal) {
      closeRemarksModal();
  }
}
