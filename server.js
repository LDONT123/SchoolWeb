// server.js
const express = require('express');
const connectDB = require('./src/config/db');

const app = express();

// Connect Database
connectDB(); // Actually call the function

// Init Middleware
app.use(express.json({ extended: false }));

app.get('/', (req, res) => res.send('Hello World! API Running!'));

// Define Routes
app.use('/api/events', require('./src/routes/eventRoutes'));
app.use('/api/news', require('./src/routes/newsRoutes'));
app.use('/api/important-dates', require('./src/routes/importantDateRoutes'));
app.use('/api/faculty', require('./src/routes/facultyRoutes'));
app.use('/api/campus-resources', require('./src/routes/campusResourceRoutes'));
app.use('/api/gallery-images', require('./src/routes/galleryImageRoutes'));
app.use('/api/documents', require('./src/routes/documentRoutes')); // Add this line

const PORT = process.env.PORT || 3000;

app.listen(PORT, () => console.log(`Server started on port ${PORT}`));
