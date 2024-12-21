async function checkLocationAndIpAddress() {
    try {
        // Fetch IP address and location details
        const response = await fetch('https://ipinfo.io/json?token=1e3887629fcd4e'); // Replace with your IPinfo token
        if (!response.ok) {
            throw new Error('Failed to fetch location data');
        }

        const data = await response.json();

        // Extract necessary information
        const ipAddress = data.ip;
        const location = data.city ? `${data.city}, ${data.region}, ${data.country}` : data.loc;

        console.log(`IP Address: ${ipAddress}`);
        console.log(`Location: ${location}`);

        return { ipAddress, location };
    } catch (error) {
        console.error('Error fetching location and IP address:', error);
        return null;
    }
}

