<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id(); // Auto-incrementing primary key
                $table->string('name'); // Department name
                $table->string('group_name'); // Group name (e.g., "OFFICE OF THE CITY MAYOR")
                $table->timestamps(); // Created at and updated at
            });
        }

        // Insert initial departments
        DB::table('departments')->insert([
            // Office of the City Mayor
            ['name' => 'Office of the City Mayor (OCM)', 'group_name' => 'OFFICE OF THE CITY MAYOR'],
            ['name' => 'Chief of Staff', 'group_name' => 'OFFICE OF THE CITY MAYOR'],
            ['name' => 'Mayors Office Staff II', 'group_name' => 'OFFICE OF THE CITY MAYOR'],

            // Sangguniang Panlungsod
            ['name' => 'Office of the Vice Mayor (OVM)', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Sangguniang Panlungsod Secretariat', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Office of Councilor Rustia', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Office of Councilor Tantoco', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Office of Councilor Santiago', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Office of Councilor Delos Santos', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Office of Councilor Gonzales', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Office of Councilor Balderrama', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Office of Councilor De Leon', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Office of Councilor Raymundo', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Office of Councilor Asilo-Gupilan', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Office of Councilor Agustin', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Office of Councilor Cruz', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Office of Councilor Martires', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Office of Councilor Enriquez', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Association of Barangay Captains (ABC)', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],
            ['name' => 'Sangguniang Kabataan Federation', 'group_name' => 'SANGGUNIANG PANLUNGSOD'],

            // Institutional Development Sector
            ['name' => 'Office of the City Administrator (OCA)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Accounting Office', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Staff', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'City Budget Office (CBO)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Human Resource and Development Office (HRDO)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Appointment Section', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Claims & Benefits Division', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Payroll Division', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Records Division', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'City Legal Office', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'City Planning & Development Office (CPDO)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'City Treasurer\'s Office (CTO)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Internal Audit Service Unit (IAS)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Public Information Office (PIO)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Community Relation and Information Office (CRIO)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Management Information Systems Office (MISO)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Technical Support Division (MISO)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Application Support Division (MISO)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'MIS ID-Section (MISO)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'City Assessor\'s Office (CAO)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Procurement Management Office (PMO)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'RPT Cash', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'RPT Billing & Collection', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'RPT Monitoring Unit', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'RPT Record Unit', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Tax Clearance/Transfer Tax Unit', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Auction Unit', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Treasury Operation & Review Division (TORD)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Treasury Cheque Section', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Community Tax Certificate Cashier', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Miscellaneous Office', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Business Tax and Miscellaneous Revenues Division (BTMRD)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Ugnayan sa Pasig / FOI', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Events and Community Kitchen', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'People\'s Law Enforcement Board (PLEB)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Office of General Services (OGS)', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Asset Management Division', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Central Supply Mgt. Division', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Records Management and Archives Division', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Land Management', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'City Records', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Commission on Audit', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'MIS PMD', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'MIS AS Non-Income', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'MIS IT', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'MIS IT - Network Admin', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'City Treasury', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Notice Service Unit', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'SWAC', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'San Antonio Annex', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],
            ['name' => 'Manggahan Annex', 'group_name' => 'INSTITUTIONAL DEVELOPMENT SECTOR'],

            // Economic Sector
            ['name' => 'Cooperative Development Office (CDO)', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Cultural Affairs and Tourism Office (CATO)', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Pasig Public Employment Service Office (PESO)', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Public Market Administration', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Consultant', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Motorpool Division', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Administrative Division', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Housekeeping Division', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Pasig City Local Economic Development and Investment Office (PCLEDIO)', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Pasig City Sports Center (PSC)', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Pasig City Museum', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Tricycle Operation and Regulatory Office (TORO)', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Pasig City Revolving Tower', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Business Regulatory Offices (CITY HALL)', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Business Regulatory Offices (AYALA)', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Tanghalang Pasigueño', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'Business Permit & License Department (BPLD)', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'BPLD Unit 1 (Pasig City Hall Annex 1)', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'BPLD Unit 2', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'BPLD Unit 3', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'BPLD Unit 4 (Pasig City Hall Annex 1)', 'group_name' => 'ECONOMIC SECTOR'],
            ['name' => 'BPLD Unit 5 (Pasig City Hall Annex 2)', 'group_name' => 'ECONOMIC SECTOR'],

            // Environmental Sector
            ['name' => 'Maybunga Rainforest Park', 'group_name' => 'ENVIRONMENTAL SECTOR'],
            ['name' => 'City Environmental and Natural Resources Office (CENRO)', 'group_name' => 'ENVIRONMENTAL SECTOR'],
            ['name' => 'Solid Waste Management Office (SWMO)', 'group_name' => 'ENVIRONMENTAL SECTOR'],
            ['name' => 'City Disaster Risk Reduction and Management Office (CDRRMO)', 'group_name' => 'ENVIRONMENTAL SECTOR'],

            // Social Services
            ['name' => 'CSWD', 'group_name' => 'SOCIAL SERVICES'],

            // Health
            ['name' => 'City Health Department (CHD)', 'group_name' => 'HEALTH'],
            ['name' => 'Drug Testing', 'group_name' => 'HEALTH'],
            ['name' => 'SATOP', 'group_name' => 'HEALTH'],
            ['name' => 'Sanitation Office', 'group_name' => 'HEALTH'],
            ['name' => 'Laboratory', 'group_name' => 'HEALTH'],
            ['name' => 'Pasig City General Hospital (PCGH)', 'group_name' => 'HEALTH'],
            ['name' => 'Medical Director', 'group_name' => 'HEALTH'],
            ['name' => 'Pasig City COVID-19 Referral Center', 'group_name' => 'HEALTH'],
            ['name' => 'GAD (Wellness Clinic)', 'group_name' => 'HEALTH'],
            ['name' => 'Pasig City Children\'s Hospital (PCCH)', 'group_name' => 'HEALTH'],
            ['name' => 'Department of Veterinary Services (DVS)', 'group_name' => 'HEALTH'],
            ['name' => 'City Health', 'group_name' => 'HEALTH'],
            ['name' => 'Pasig CHAMP', 'group_name' => 'HEALTH'],

            // Education
            ['name' => 'Pasig City Institute of Science & Technology (PCIST)', 'group_name' => 'EDUCATION'],
            ['name' => 'Bambang', 'group_name' => 'EDUCATION'],
            ['name' => 'Sta. Lucia', 'group_name' => 'EDUCATION'],
            ['name' => 'Special Children Educational Institution (SCEI/SPED Special Education)', 'group_name' => 'EDUCATION'],
            ['name' => 'Barangay Computer Literacy Program (BCLP)', 'group_name' => 'EDUCATION'],
            ['name' => 'Pamantasan ng Lungsod ng Pasig (PLP)', 'group_name' => 'EDUCATION'],
            ['name' => 'Pasig City Science High School (PCSHS)', 'group_name' => 'EDUCATION'],
            ['name' => 'Education Unit (EDUC)', 'group_name' => 'EDUCATION'],
            ['name' => 'City Library and Discovery Centrum', 'group_name' => 'EDUCATION'],
            ['name' => 'Pasig City Scholar Office', 'group_name' => 'EDUCATION'],

            // Peace and Order
            ['name' => 'Pasig City Anti-Drug Abuse Office (PCADAO)', 'group_name' => 'PEACE AND ORDER'],
            ['name' => 'Traffic and Parking Mgt. Office (TPMO)', 'group_name' => 'PEACE AND ORDER'],
            ['name' => 'Peace and Order Department (POD)', 'group_name' => 'PEACE AND ORDER'],
            ['name' => 'Public and Safety Division', 'group_name' => 'PEACE AND ORDER'],
            ['name' => 'Kabataan Resource Patrol Division', 'group_name' => 'PEACE AND ORDER'],
            ['name' => 'Bantay Pasig Division', 'group_name' => 'PEACE AND ORDER'],

            // Social Welfare
            ['name' => 'City Social Welfare and Development Office (CSWDO)', 'group_name' => 'SOCIAL WELFARE'],
            ['name' => 'Person with Disability Affairs Office (PDAO)', 'group_name' => 'SOCIAL WELFARE'],
            ['name' => 'Gender and Development Office (GAD)', 'group_name' => 'SOCIAL WELFARE'],
            ['name' => 'Bahay Kalinga Ng Pasigueña Center (BHPC)', 'group_name' => 'SOCIAL WELFARE'],
            ['name' => 'Local Youth Development Office (LYDO)', 'group_name' => 'SOCIAL WELFARE'],
            ['name' => 'Office of the Senior Citizen Affairs (OSCA)', 'group_name' => 'SOCIAL WELFARE'],
            ['name' => 'Youth Development Center (YDC)', 'group_name' => 'SOCIAL WELFARE'],

            // Housing
            ['name' => 'Pasig Urban Settlement Office (PUSO)', 'group_name' => 'HOUSING'],

            // Population
            ['name' => 'City Civil Registry Office', 'group_name' => 'POPULATION'],

            // Infrastructure Sector
            ['name' => 'City Engineer\'s Office (CEO)', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Construction, Occupational, Safety and Health Section', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Electrical Infrastructure Section', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Electrical Maintenance Section', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Flood Control Operation/Mitigation Section', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'General Maintenance Division (Drainage)', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'General Maintenance Division (Water Mgt.)', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Horizontal Section', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Vertical Section', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Planning, Programming and Construction Division', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'PPCD - Architectural Office', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Quality Control Section', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Road Maintenance Section', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Special Projects Section', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Structural Section', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Survey Section', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Excavation Permit Section', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'City Parks and Playground Section', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Office of the Building Official (OBO)', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'City Transportation Development and Management Office (CTDMO)', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Electrical Mechanical', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'City Engineering Office', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'City Engineering Office II', 'group_name' => 'INFRASTRUCTURE SECTOR'],
            ['name' => 'Building Maintenance', 'group_name' => 'INFRASTRUCTURE SECTOR'],
        ]);
    }


public function down()
{
    Schema::dropIfExists('departments');
}
};