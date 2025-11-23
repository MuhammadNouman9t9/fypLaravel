<x-landing-layout title="Contact">
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900">Contact Us</h1>
                <p class="mt-4 text-lg text-gray-600">Get in touch with our team</p>
            </div>

            <div class="max-w-2xl mx-auto">
                <div class="bg-gray-50 rounded-lg p-8">
                    <form class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Your Name">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="your@email.com">
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                            <textarea id="message" name="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Your message"></textarea>
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mt-12 text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Other Ways to Reach Us</h3>
                    <div class="space-y-2 text-gray-600">
                        <p>Email: info@safenest.com</p>
                        <p>Phone: +1 (555) 123-4567</p>
                        <p>Address: 123 Security Street, Safety City, SC 12345</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-landing-layout>

