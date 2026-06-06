const express = require('express');
const app = express();
app.use(express.json());

app.post('/submit-appointment', (req, res) => {
    const { email } = req.body;

    if (!email) {
        return res.status(400).json({ success: false, message: 'Email is required' });
    }

    // Simulate successful appointment creation
    const appointnumber = Math.floor(100000 + Math.random() * 900000); // Generate random number

    // Ideally, send an email with `appointnumber` here using an email service
    console.log(`Email sent to ${email} with Appointment Number: ${appointnumber}`);

    res.json({ success: true, appointnumber });
});

const PORT = 3000;
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));
