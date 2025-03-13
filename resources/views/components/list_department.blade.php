    <!-- Default option -->
    <option value="{{ Auth::user()->account_type === 'end_user' ? Auth::user()->department : '' }}">
        {{ Auth::user()->account_type === 'end_user' ? Auth::user()->department : 'Select Department' }}
    </option>
</select>

<script>
    async function populateDepartments() {
        const select = document.getElementById('department');

        try {
            const response = await fetch('/departments');
            if (!response.ok) throw new Error('Failed to fetch departments');
            const departments = await response.json();

            select.innerHTML = ''; // Clear default option

            for (const [groupName, groupDepartments] of Object.entries(departments)) {
                const optgroup = document.createElement('optgroup');
                optgroup.label = groupName;

                groupDepartments.forEach(department => {
                    const option = document.createElement('option');
                    option.value = department.name;
                    option.text = department.name;

                    if (department.name === "{{ Auth::user()->department }}") {
                        option.selected = true;
                    }

                    optgroup.appendChild(option);
                });

                select.appendChild(optgroup);
            }
        } catch (error) {
            console.error('Error fetching departments:', error);
            select.innerHTML = '<option value="">Failed to load departments. Please try again later.</option>';
        }
    }

    document.addEventListener('DOMContentLoaded', populateDepartments);
</script>