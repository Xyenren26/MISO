<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Ticket Form</title>
    <link rel="stylesheet" href="{{ asset('css/ticket_components_Style.css') }}">
</head>
<body>
    <!-- Ticket Form Container -->
    <div class="content-wrapper">
        <div class="ticket-form-container">
        <button class="close-modal" onclick="closeTicketFormModal()">✖</button>
            <h2>Technical Service Slip</h2> <!-- New heading added here -->
            <form id="ticketForm" action="{{ route('ticket.store') }}" method="POST">
            @csrf
                <div class="control-number" id="controlNumber">
                    {{ $formattedControlNo }}
                    <input type="hidden" name="controlNumber" value="{{ $formattedControlNo }}">
                </div>
           
                <!-- Row 1: Personal Information -->
                <fieldset>
                    <legend>Personal Information</legend>
                    <div class="personal-info-container">
                        <div class="personal-info-field">
                            <label for="first-name">First Name:</label>
                            <input type="text" id="first-name" name="first-name" placeholder="First Name" required>
                        </div>
                        <div class="personal-info-field">
                            <label for="last-name">Last Name:</label>
                            <input type="text" id="last-name" name="last-name" placeholder="Last Name" required>
                        </div>
                        <div class="personal-info-field">
                        <label for="department">Department</label>
                            <select id="department" name="department" class="department-select" required>
                                <option value="">Select Department</option>

                                <!-- Office of the City Mayor -->
                                <optgroup label="OFFICE OF THE CITY MAYOR">
                                <option value="Office of the City Mayor (OCM)">Office of the City Mayor (OCM)</option>
                                <option value="Chief of Staff">Chief of Staff</option>
                                <option value="Mayors Office Staff II">Mayors Office Staff II</option>
                                </optgroup>

                                <!-- Sangguniang Panlungsod -->
                                <optgroup label="SANGGUNIANG PANLUNGSOD">
                                <option value="Office of the Vice Mayor (OVM)">Office of the Vice Mayor (OVM)</option>
                                <option value="Sangguniang Panlungsod Secretariat">Sangguniang Panlungsod Secretariat</option>
                                <option value="Office of Councilor Rustia">Office of Councilor Rustia</option>
                                <option value="Office of Councilor Tantoco">Office of Councilor Tantoco</option>
                                <option value="Office of Councilor Santiago">Office of Councilor Santiago</option>
                                <option value="Office of Councilor Delos Santos">Office of Councilor Delos Santos</option>
                                <option value="Office of Councilor Gonzales">Office of Councilor Gonzales</option>
                                <option value="Office of Councilor Balderrama">Office of Councilor Balderrama</option>
                                <option value="Office of Councilor De Leon">Office of Councilor De Leon</option>
                                <option value="Office of Councilor Raymundo">Office of Councilor Raymundo</option>
                                <option value="Office of Councilor Asilo-Gupilan">Office of Councilor Asilo-Gupilan</option>
                                <option value="Office of Councilor Agustin">Office of Councilor Agustin</option>
                                <option value="Office of Councilor Cruz">Office of Councilor Cruz</option>
                                <option value="Office of Councilor Martires">Office of Councilor Martires</option>
                                <option value="Office of Councilor Enriquez">Office of Councilor Enriquez</option>
                                <option value="Association of Barangay Captains (ABC)">Association of Barangay Captains (ABC)</option>
                                <option value="Sangguniang Kabataan Federation">Sangguniang Kabataan Federation</option>
                                </optgroup>

                                <!-- Institutional Development Sector -->
                                <optgroup label="INSTITUTIONAL DEVELOPMENT SECTOR">
                                <option value="Office of the City Administrator (OCA)">Office of the City Administrator (OCA)</option>
                                <option value="Accounting Office">Accounting Office</option>
                                <option value="Staff">Staff</option>
                                <option value="City Budget Office (CBO)">City Budget Office (CBO)</option>
                                <option value="Human Resource and Development Office (HRDO)">Human Resource and Development Office (HRDO)</option>
                                <option value="Appointment Section">Appointment Section</option>
                                <option value="Claims & Benefits Division">Claims & Benefits Division</option>
                                <option value="Payroll Division">Payroll Division</option>
                                <option value="Records Division">Records Division</option>
                                <option value="City Legal Office">City Legal Office</option>
                                <option value="City Planning & Development Office (CPDO)">City Planning & Development Office (CPDO)</option>
                                <option value="City Treasurer's Office (CTO)">City Treasurer's Office (CTO)</option>
                                <option value="Internal Audit Service Unit (IAS)">Internal Audit Service Unit (IAS)</option>
                                <option value="Public Information Office (PIO)">Public Information Office (PIO)</option>
                                <option value="Community Relation and Information Office (CRIO)">Community Relation and Information Office (CRIO)</option>
                                <option value="Management Information Systems Office (MISO)">Management Information Systems Office (MISO)</option>
                                <option value="Technical Support Division (MISO)">Technical Support Division (MISO)</option>
                                <option value="Application Support Division (MISO)">Application Support Division (MISO)</option>
                                <option value="MIS ID-Section (MISO)">MIS ID-Section (MISO)</option>
                                <option value="City Assessor's Office (CAO)">City Assessor's Office (CAO)</option>
                                <option value="Procurement Management Office (PMO)">Procurement Management Office (PMO)</option>
                                <option value="RPT Cash">RPT Cash</option>
                                <option value="RPT Billing & Collection">RPT Billing & Collection</option>
                                <option value="RPT Monitoring Unit">RPT Monitoring Unit</option>
                                <option value="RPT Record Unit">RPT Record Unit</option>
                                <option value="Tax Clearance/Transfer Tax Unit">Tax Clearance/Transfer Tax Unit</option>
                                <option value="Auction Unit">Auction Unit</option>
                                <option value="Treasury Operation & Review Division (TORD)">Treasury Operation & Review Division (TORD)</option>
                                <option value="Treasury Cheque Section">Treasury Cheque Section</option>
                                <option value="Community Tax Certificate Cashier">Community Tax Certificate Cashier</option>
                                <option value="Miscellaneous Office">Miscellaneous Office</option>
                                <option value="Business Tax and Miscellaneous Revenues Division (BTMRD)">Business Tax and Miscellaneous Revenues Division (BTMRD)</option>
                                <option value="Ugnayan sa Pasig / FOI">Ugnayan sa Pasig / FOI</option>
                                <option value="Events and Community Kitchen">Events and Community Kitchen</option>
                                <option value="People's Law Enforcement Board (PLEB)">People's Law Enforcement Board (PLEB)</option>
                                <option value="Office of General Services (OGS)">Office of General Services (OGS)</option>
                                <option value="Asset Management Division">Asset Management Division</option>
                                <option value="Central Supply Mgt. Division">Central Supply Mgt. Division</option>
                                <option value="Records Management and Archives Division">Records Management and Archives Division</option>
                                <option value="Land Management">Land Management</option>
                                <option value="City Records">City Records</option>
                                <option value="Commission on Audit">Commission on Audit</option>
                                <option value="MIS PMD">MIS PMD</option>
                                <option value="MIS AS Non-Income">MIS AS Non-Income</option>
                                <option value="MIS IT">MIS IT</option>
                                <option value="MIS IT - Network Admin">MIS IT - Network Admin</option>
                                <option value="City Treasury">City Treasury</option>
                                <option value="Notice Service Unit">Notice Service Unit</option>
                                <option value="SWAC">SWAC</option>
                                <option value="San Antonio Annex">San Antonio Annex</option>
                                <option value="Manggahan Annex">Manggahan Annex</option>
                                </optgroup>

                                <!-- Economic Sector -->
                                <optgroup label="ECONOMIC SECTOR">
                                <option value="Cooperative Development Office (CDO)">Cooperative Development Office (CDO)</option>
                                <option value="Cultural Affairs and Tourism Office (CATO)">Cultural Affairs and Tourism Office (CATO)</option>
                                <option value="Pasig Public Employment Service Office (PESO)">Pasig Public Employment Service Office (PESO)</option>
                                <option value="Public Market Administration">Public Market Administration</option>
                                <option value="Consultant">Consultant</option>
                                <option value="Motorpool Division">Motorpool Division</option>
                                <option value="Administrative Division">Administrative Division</option>
                                <option value="Housekeeping Division">Housekeeping Division</option>
                                <option value="Pasig City Local Economic Development and Investment Office (PCLEDIO)">Pasig City Local Economic Development and Investment Office (PCLEDIO)</option>
                                <option value="Pasig City Sports Center (PSC)">Pasig City Sports Center (PSC)</option>
                                <option value="Pasig City Museum">Pasig City Museum</option>
                                <option value="Tricycle Operation and Regulatory Office (TORO)">Tricycle Operation and Regulatory Office (TORO)</option>
                                <option value="Pasig City Revolving Tower">Pasig City Revolving Tower</option>
                                <option value="Business Regulatory Offices (CITY HALL)">Business Regulatory Offices (CITY HALL)</option>
                                <option value="Business Regulatory Offices (AYALA)">Business Regulatory Offices (AYALA)</option>
                                <option value="Tanghalang Pasigueño">Tanghalang Pasigueño</option>
                                <option value="Business Permit & License Department (BPLD)">Business Permit & License Department (BPLD)</option>
                                <option value="BPLD Unit 1 (Pasig City Hall Annex 1)">BPLD Unit 1 (Pasig City Hall Annex 1)</option>
                                <option value="BPLD Unit 2">BPLD Unit 2</option>
                                <option value="BPLD Unit 3">BPLD Unit 3</option>
                                <option value="BPLD Unit 4 (Pasig City Hall Annex 1)">BPLD Unit 4 (Pasig City Hall Annex 1)</option>
                                <option value="BPLD Unit 5 (Pasig City Hall Annex 2)">BPLD Unit 5 (Pasig City Hall Annex 2)</option>
                                </optgroup>

                                <!-- Environmental Sector -->
                                <optgroup label="ENVIRONMENTAL SECTOR">
                                <option value="Maybunga Rainforest Park">Maybunga Rainforest Park</option>
                                <option value="City Environmental and Natural Resources Office (CENRO)">City Environmental and Natural Resources Office (CENRO)</option>
                                <option value="Solid Waste Management Office (SWMO)">Solid Waste Management Office (SWMO)</option>
                                <option value="City Disaster Risk Reduction and Management Office (CDRRMO)">City Disaster Risk Reduction and Management Office (CDRRMO)</option>
                                </optgroup>

                                <!-- Social Services -->
                                <optgroup label="SOCIAL SERVICES">
                                <option value="CSWD">CSWD</option>
                                </optgroup>

                                <!-- Health -->
                                <optgroup label="HEALTH">
                                <option value="City Health Department (CHD)">City Health Department (CHD)</option>
                                <option value="Drug Testing">Drug Testing</option>
                                <option value="SATOP">SATOP</option>
                                <option value="Sanitation Office">Sanitation Office</option>
                                <option value="Laboratory">Laboratory</option>
                                <option value="Pasig City General Hospital (PCGH)">Pasig City General Hospital (PCGH)</option>
                                <option value="Medical Director">Medical Director</option>
                                <option value="Pasig City COVID-19 Referral Center">Pasig City COVID-19 Referral Center</option>
                                <option value="GAD (Wellness Clinic)">GAD (Wellness Clinic)</option>
                                <option value="Pasig City Children's Hospital (PCCH)">Pasig City Children's Hospital (PCCH)</option>
                                <option value="Department of Veterinary Services (DVS)">Department of Veterinary Services (DVS)</option>
                                <option value="City Health">City Health</option>
                                <option value="Pasig CHAMP">Pasig CHAMP</option>
                                </optgroup>

                                <!-- Education -->
                                <optgroup label="EDUCATION">
                                <option value="Pasig City Institute of Science & Technology (PCIST)">Pasig City Institute of Science & Technology (PCIST)</option>
                                <option value="Bambang">Bambang</option>
                                <option value="Sta. Lucia">Sta. Lucia</option>
                                <option value="Special Children Educational Institution (SCEI/SPED Special Education)">Special Children Educational Institution (SCEI/SPED Special Education)</option>
                                <option value="Barangay Computer Literacy Program (BCLP)">Barangay Computer Literacy Program (BCLP)</option>
                                <option value="Pamantasan ng Lungsod ng Pasig (PLP)">Pamantasan ng Lungsod ng Pasig (PLP)</option>
                                <option value="Pasig City Science High School (PCSHS)">Pasig City Science High School (PCSHS)</option>
                                <option value="Education Unit (EDUC)">Education Unit (EDUC)</option>
                                <option value="City Library and Discovery Centrum">City Library and Discovery Centrum</option>
                                <option value="Pasig City Scholar Office">Pasig City Scholar Office</option>
                                </optgroup>

                                <!-- Peace and Order -->
                                <optgroup label="PEACE AND ORDER">
                                <option value="Pasig City Anti-Drug Abuse Office (PCADAO)">Pasig City Anti-Drug Abuse Office (PCADAO)</option>
                                <option value="Traffic and Parking Mgt. Office (TPMO)">Traffic and Parking Mgt. Office (TPMO)</option>
                                <option value="Peace and Order Department (POD)">Peace and Order Department (POD)</option>
                                <option value="Public and Safety Division">Public and Safety Division</option>
                                <option value="Kabataan Resource Patrol Division">Kabataan Resource Patrol Division</option>
                                <option value="Bantay Pasig Division">Bantay Pasig Division</option>
                                </optgroup>

                                <!-- Social Welfare -->
                                <optgroup label="SOCIAL WELFARE">
                                <option value="City Social Welfare and Development Office (CSWDO)">City Social Welfare and Development Office (CSWDO)</option>
                                <option value="Person with Disability Affairs Office (PDAO)">Person with Disability Affairs Office (PDAO)</option>
                                <option value="Gender and Development Office (GAD)">Gender and Development Office (GAD)</option>
                                <option value="Bahay Kalinga Ng Pasigueña Center (BHPC)">Bahay Kalinga Ng Pasigueña Center (BHPC)</option>
                                <option value="Local Youth Development Office (LYDO)">Local Youth Development Office (LYDO)</option>
                                <option value="Office of the Senior Citizen Affairs (OSCA)">Office of the Senior Citizen Affairs (OSCA)</option>
                                <option value="Youth Development Center (YDC)">Youth Development Center (YDC)</option>
                                </optgroup>

                                <!-- Housing -->
                                <optgroup label="HOUSING">
                                <option value="Pasig Urban Settlement Office (PUSO)">Pasig Urban Settlement Office (PUSO)</option>
                                </optgroup>

                                <!-- Population -->
                                <optgroup label="POPULATION">
                                <option value="City Civil Registry Office">City Civil Registry Office</option>
                                </optgroup>

                                <!-- Infrastructure Sector -->
                                <optgroup label="INFRASTRUCTURE SECTOR">
                                <option value="City Engineer's Office (CEO)">City Engineer's Office (CEO)</option>
                                <option value="Construction, Occupational, Safety and Health Section">Construction, Occupational, Safety and Health Section</option>
                                <option value="Electrical Infrastructure Section">Electrical Infrastructure Section</option>
                                <option value="Electrical Maintenance Section">Electrical Maintenance Section</option>
                                <option value="Flood Control Operation/Mitigation Section">Flood Control Operation/Mitigation Section</option>
                                <option value="General Maintenance Division (Drainage)">General Maintenance Division (Drainage)</option>
                                <option value="General Maintenance Division (Water Mgt.)">General Maintenance Division (Water Mgt.)</option>
                                <option value="Horizontal Section">Horizontal Section</option>
                                <option value="Vertical Section">Vertical Section</option>
                                <option value="Planning, Programming and Construction Division">Planning, Programming and Construction Division</option>
                                <option value="PPCD - Architectural Office">PPCD - Architectural Office</option>
                                <option value="Quality Control Section">Quality Control Section</option>
                                <option value="Road Maintenance Section">Road Maintenance Section</option>
                                <option value="Special Projects Section">Special Projects Section</option>
                                <option value="Structural Section">Structural Section</option>
                                <option value="Survey Section">Survey Section</option>
                                <option value="Excavation Permit Section">Excavation Permit Section</option>
                                <option value="City Parks and Playground Section">City Parks and Playground Section</option>
                                <option value="Office of the Building Official (OBO)">Office of the Building Official (OBO)</option>
                                <option value="City Transportation Development and Management Office (CTDMO)">City Transportation Development and Management Office (CTDMO)</option>
                                <option value="Electrical Mechanical">Electrical Mechanical</option>
                                <option value="City Engineering Office">City Engineering Office</option>
                                <option value="City Engineering Office II">City Engineering Office II</option>
                                <option value="Building Maintenance">Building Maintenance</option>
                                </optgroup>
                              
                            </select>
                        </div>
                    </div>
                </fieldset>

                <!-- Row 2: Ticket Details -->
                <fieldset>
                    <legend>Ticket Details</legend>
                    <div class="form-row">
                        <label for="concern">Concern/Problem:</label>
                        <div class="issue-dropdown">
                            <button class="issue-dropbtn" id="selectedConcerns">Select Concern</button>
                            <div class="issue-dropdown-content">
                                <label><input type="checkbox" name="issues[]" value="Hardware Issue" onchange="updateSelectedConcerns()"> Hardware Issue</label>
                                <label><input type="checkbox" name="issues[]" value="Software Issue" onchange="updateSelectedConcerns()"> Software Issue</label>
                                <label><input type="checkbox" name="issues[]" value="File Transfer" onchange="updateSelectedConcerns()"> File Transfer</label>
                                <label><input type="checkbox" name="issues[]" value="Network Connectivity" onchange="updateSelectedConcerns()"> Network Connectivity</label>
                                <label><input type="checkbox" name="issues[]" value="Other" id="otherIssueCheckbox" onchange="toggleOtherInput(); updateSelectedConcerns()"> Other: Specify</label>
                            </div>
                        </div>

                        <div id="otherConcernContainer" style="display: none;">
                            <label for="otherConcern">Please Specify:</label>
                            <input type="text" id="otherConcern" name="otherConcern" placeholder="Specify your concern" oninput="updateSelectedConcerns()">
                        </div>



                        <label for="category">Priority:</label>
                        <select id="category" name="category" required>
                            <option value="" disabled selected>Select Priority</option>
                            <option value="urgent">Urgent</option>
                            <option value="semi-urgent">Semi-Urgent</option>
                            <option value="non-urgent">Non-Urgent</option>
                        </select>
                        
                        <label for="employeeId">Employee ID:</label>
                        <input type="text" id="employeeId" name="employeeId" required>
                        <span id="error-message" style="color: red; display: none;">Employee ID must be a 7-digit whole number.</span>

                    </div>
                </fieldset>

                    <!-- Row 3: Support Details -->
                <fieldset>
                    <legend>Support Details</legend>
                    <div class="support-details-container">
                        <div class="support-details-field">
                            <label for="technicalSupport">Technical Support By:</label>
                            <select id="technicalSupport" name="technicalSupport" required>
                                <option value="" disabled selected>Select Technical Support</option>
                                @foreach($technicalSupports as $tech)
                                    <option value="{{ $tech->employee_id }}">{{ $tech->first_name }} {{ $tech->last_name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </fieldset>

                <!-- Error message container -->
                <div id="errorMessage" style="color: red; display: none; text-align:center; margin-top: 10px;">
                    <p>Please fill in all fields.</p>
                </div>

                <!-- Submit Button -->
                <div class="form-actions">
                    <button type="submit" class="submit-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>




<script>
    
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

</script>


</body>
</html>
