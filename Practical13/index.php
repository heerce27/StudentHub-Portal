<!DOCTYPE html>
<html>
<head>
    <title>Student API CRUD</title>
</head>
<body>

<h1>Student Management — Fetch API</h1>

<p id="message">Loading students...</p>

<form id="studentForm">
    <input type="hidden" id="id">

    <input id="name" placeholder="Student name" required>
    <input id="email" type="email" placeholder="Email" required>

    <select id="course">
        <option value="BCA">BCA</option>
        <option value="BSc IT">BSc IT</option>
        <option value="BTech">BTech</option>
    </select>

    <button type="submit">Save Student</button>
    <button type="button" onclick="resetForm()">Clear</button>
</form>

<h2>Student List</h2>

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="studentList"></tbody>
</table>

<script>
const api = 'api.php';

function showMessage(text) {
    document.getElementById('message').textContent = text;
}

async function loadStudents() {
    showMessage('Loading students...');

    try {
        const response = await fetch(api);
        const students = await response.json();

        if (!response.ok) {
            throw new Error(students.message || 'Unable to load students');
        }

        const list = document.getElementById('studentList');
        list.innerHTML = '';

        students.forEach(student => {
            const row = document.createElement('tr');

            [student.id, student.name, student.email, student.course]
                .forEach(value => {
                    const cell = document.createElement('td');
                    cell.textContent = value;
                    row.appendChild(cell);
                });

            const actions = document.createElement('td');

            const edit = document.createElement('button');
            edit.textContent = 'Edit';
            edit.onclick = () => editStudent(student);

            const del = document.createElement('button');
            del.textContent = 'Delete';
            del.onclick = () => deleteStudent(student.id);

            actions.append(edit, del);
            row.appendChild(actions);
            list.appendChild(row);
        });

        showMessage('Students loaded successfully.');
    } catch (error) {
        showMessage('Error: ' + error.message);
    }
}

document.getElementById('studentForm').onsubmit = async function(e) {
    e.preventDefault();

    const id = document.getElementById('id').value;

    const student = {
        name: document.getElementById('name').value.trim(),
        email: document.getElementById('email').value.trim(),
        course: document.getElementById('course').value
    };

    if (!student.name || !student.email) {
        showMessage('Please enter all details.');
        return;
    }

    try {
        const response = await fetch(api, {
            method: id ? 'PUT' : 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(id ? {...student, id: Number(id)} : student)
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Request failed');
        }

        showMessage(result.message);
        resetForm();
        await loadStudents();
    } catch (error) {
        showMessage('Error: ' + error.message);
    }
};

function editStudent(student) {
    document.getElementById('id').value = student.id;
    document.getElementById('name').value = student.name;
    document.getElementById('email').value = student.email;
    document.getElementById('course').value = student.course;

    showMessage('Editing student ID ' + student.id);
}

function resetForm() {
    document.getElementById('studentForm').reset();
    document.getElementById('id').value = '';
}

async function deleteStudent(id) {
    if (!confirm('Delete this student?')) return;

    try {
        const response = await fetch(api, {
            method: 'DELETE',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: Number(id)})
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Delete failed');
        }

        showMessage(result.message);
        await loadStudents();
    } catch (error) {
        showMessage('Error: ' + error.message);
    }
}

loadStudents();
</script>

</body>
</html>