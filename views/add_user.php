<h2>Add New User</h2>
<form method="POST" action="index.php">
    <div class="form-group">
        <label>User Type:</label>
        <select name="user_type" id="user_type" onchange="toggleFields()">
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>Name:</label>
        <input type="text" name="name" required>
    </div>
    
    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" required>
    </div>
    
    <div id="student_fields">
        <div class="form-group">
            <label>Grade:</label>
            <input type="text" name="grade">
        </div>
        <div class="form-group">
            <label>Major:</label>
            <input type="text" name="major">
        </div>
    </div>
    
    <div id="teacher_fields" style="display: none;">
        <div class="form-group">
            <label>Subject:</label>
            <input type="text" name="subject">
        </div>
        <div class="form-group">
            <label>Experience (years):</label>
            <input type="number" name="experience">
        </div>
    </div>
    
    <button type="submit" name="add_user">Add User</button>
</form>

<script>
function toggleFields() {
    const userType = document.getElementById('user_type').value;
    document.getElementById('student_fields').style.display = 
        userType === 'student' ? 'block' : 'none';
    document.getElementById('teacher_fields').style.display = 
        userType === 'teacher' ? 'block' : 'none';
}
</script>