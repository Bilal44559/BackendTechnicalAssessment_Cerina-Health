**Part 4: Critical Incident Response**

**Incident 1:** Database Performance

**Questions**:

**1\. What's causing the issue?**  
**Ans**: The issue is caused by querying a very large `appointments` table (5 million records) without proper indexing on `doctor_id` and `appointment_date`. This results in full table scans, high database CPU usage (95%), and slow API responses. Using `SELECT *` further increases load by fetching unnecessary data.

**2\. How would you fix it immediately (within 15 minutes)?**  
**Ans**: I would add a composite index on `doctor_id` and `appointment_date` to optimize the query. I would also limit the query to only required columns instead of using `SELECT *`. If needed, I would temporarily enable caching for this endpoint to reduce database load and stabilize performance.

**3\. What long-term optimizations would you implement?**  
**Ans**: Long-term improvements would include implementing Redis caching, adding pagination, archiving old appointment records, optimizing query patterns, and introducing read replicas for scaling read traffic. Continuous monitoring using slow query logs or performance monitoring tools would also be implemented.

**Incident 2**: Production Bug

**Questions**:

**1\. What's your immediate action plan?**  
**Ans**: I would first stop further impact by rolling back the recent deployment or disabling the appointment booking feature temporarily. I would inform stakeholders, monitor logs in real time, and ensure failed requests are logged properly to prevent additional user impact.

**2\. How would you investigate the root cause?**  
**Ans**: I would review the deployment changes, especially database migrations and validation logic related to appointments. I would analyze error logs to identify which constraint failed (foreign key, unique constraint, or null value) and try to reproduce the issue in staging using the same data and request payloads.

**3\. How would you prevent this in the future?**  
**Ans**: To prevent this in the future, I would enforce stronger input validation, add database transaction handling with proper error responses, and improve automated testing around critical flows.

**Incident 3**: Security Breach

**Questions**:

1**. What security vulnerability likely exists?**  
**Ans**: The issue is likely due to missing or incorrect authorization checks, allowing authenticated users to access other patients’ data by changing the patient ID in the API request.

**2\. What immediate steps would you take?**  
**Ans**: I would immediately revoke the compromised tokens, block or rate-limit the suspicious IP address, and temporarily disable the affected endpoint if needed. I would also audit access logs to assess the scope of data exposure and notify relevant stakeholders.

**3\. What security measures would you implement to prevent this?**  
**Ans**: I would enforce strict authorization policies to ensure users can only access their own data, implement role-based access control, add API rate limiting, and improve logging and monitoring. 

