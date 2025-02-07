<style>
#equipmentItemsSection {
    display: block;
    visibility: visible;
}
</style>
<!-- View Deployment Modal -->
<div id="deploymentview" class="deploymentmodal" style="display: none;">
    <div class="modal-content-deployment">
        <span class="close" onclick="closeDeploymentview()">&times;</span>
        <div class="form-container">
            <header>
                <div class="logo">
                    <img src="images/systemlogo.png" alt="Logo">
                </div>
                <div class="title">
                    <h1>IT EQUIPMENT / SOFTWARE / I.S. ACKNOWLEDGEMENT RECEIPT FORM</h1>
                    <p>Management Information System Office</p>
                </div>
            </header>
            <form id="deploymentForm">
                <table>
                    <tr>
                        <th colspan="4">Purpose</th>
                        <td colspan="4">
                            <textarea id="purpose" name="purpose" rows="2" style="width: 98%;" readonly></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>Control Number:</th>
                        <td colspan="3"><input id="control_number" type="text" name="control_number" readonly></td>
                        <th>Status:</th>
                        <td><input id="status_new" type="checkbox" name="status" value="new" disabled> New</td>
                        <td><input id="status_used" type="checkbox" name="status" value="used" disabled> Used</td>
                    </tr>
                    <tr>
                        <th>Components:</th>
                        <td><input type="checkbox" id="component_cpu" disabled> CPU</td>
                        <td><input type="checkbox" id="component_monitor" disabled> Monitor</td>
                        <td><input type="checkbox" id="component_printer" disabled> Printer</td>
                        <td><input type="checkbox" id="component_ups" disabled> UPS</td>
                        <td><input type="checkbox" id="component_switch" disabled> Switch</td>
                        <td><input type="checkbox" id="component_keyboard" disabled> Keyboard</td>
                        <td><input type="checkbox" id="component_mouse" disabled> Mouse</td>
                    </tr>
                    <tr>
                        <th colspan="2">Software / I.S.</th>
                        <td colspan="6" id="software">
                            <input type="checkbox" id="software_google_workspace" disabled> Google Workspace
                            <input type="checkbox" id="software_ms_office" disabled> MS Office
                            <input type="checkbox" id="software_others" disabled> Others
                        </td>
                    </tr>
                </table>

                <!-- Equipment Items Section -->
                <div id="equipmentItemsSection">
                    <table>
                        <tr>
                            <th>Description</th>
                            <th>Serial Number</th>
                            <th>Quantity</th>
                        </tr>
                        <tr>
                            <td><input id="equipment_description" type="text" name="description" readonly></td>
                            <td><input id="equipment_serial_number" type="text" name="serial_number" readonly></td>
                            <td><input id="equipment_quantity" type="number" name="quantity" readonly></td>
                        </tr>
                    </table>
                </div>
                <table>
                    <tr>
                        <th>Brand/Name</th>
                        <td colspan="7"><input id="brand_name" type="text" name="brand_name" readonly></td>
                    </tr>
                    <tr>
                        <th>Specification</th>
                        <td colspan="7"><textarea id="specification" name="specification" readonly></textarea></td>
                    </tr>
                    <tr>
                        <th>Received By</th>
                        <td><input id="received_by" type="text" name="received_by" readonly></td>
                        <th>Issued By</th>
                        <td><input id="issued_by" type="text" name="issued_by" readonly></td>
                        <th>Noted By</th>
                        <td><input id="noted_by" type="text" name="noted_by" readonly></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td><input id="received_date" type="date" name="received_date" readonly></td>
                        <th>Date</th>
                        <td><input id="issued_date" type="date" name="issued_date" readonly></td>
                        <th>Date</th>
                        <td><input id="noted_date" type="date" name="noted_date" readonly></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
