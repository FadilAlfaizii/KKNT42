import React from 'react';
import { Head, Link } from '@inertiajs/react';
import Layout from '../Layouts/Layout';

export default function DetailArtikel({ article, relatedArticles = [] }) {
    const displayArticle = article;
    
    // Helper function to format date
    const formatDate = (dateString) => {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric' 
        });
    };

    return (
        <Layout>
            <Head title={displayArticle?.title || 'Detail Artikel'} />
            <div className="min-h-screen bg-gray-50 pt-20">
                {/* Header Image */}
                {displayArticle?.image && (
                    <div className="w-full h-64 md:h-96 bg-gray-200 relative">
                        <img 
                            src={displayArticle.image} 
                            alt={displayArticle.title} 
                            className="w-full h-full object-cover"
                            onError={(e) => { 
                                e.target.src = 'https://via.placeholder.com/1200x400?text=Artikel'; 
                            }}
                        />
                        <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div className="absolute bottom-0 left-0 right-0 container mx-auto px-4 py-4">
                            {displayArticle?.category && (
                                <span className="bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                                    {displayArticle.category}
                                </span>
                            )}
                        </div>
                    </div>
                )}

                <div className="container mx-auto px-4 py-8">
                    <div className="max-w-4xl mx-auto">
                        {/* Breadcrumb */}
                        <nav className="mb-6 text-sm">
                            <ol className="flex items-center space-x-2 text-gray-500">
                                <li><Link href="/" className="hover:text-green-600">Beranda</Link></li>
                                <li><span className="mx-2">/</span></li>
                                <li><Link href="/artikel" className="hover:text-green-600">Artikel</Link></li>
                                <li><span className="mx-2">/</span></li>
                                <li className="text-gray-800 truncate max-w-xs">{displayArticle?.title}</li>
                            </ol>
                        </nav>

                        {/* Article Content */}
                        <article className="bg-white rounded-lg shadow-md p-6 md:p-8 mb-8">
                            <h1 className="text-2xl md:text-4xl font-bold text-gray-800 mb-4">
                                {displayArticle?.title}
                            </h1>
                            
                            {/* Author Info */}
                            <div className="flex items-center justify-between border-b border-gray-200 pb-4 mb-6">
                                <div className="flex items-center space-x-4">
                                    {displayArticle?.author && (
                                        <div className="flex items-center">
                                            <div className="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                                {displayArticle.author.charAt(0)}
                                            </div>
                                            <div>
                                                <p className="font-semibold text-gray-800">{displayArticle.author}</p>
                                                {displayArticle?.author_bio && (
                                                    <p className="text-sm text-gray-500">{displayArticle.author_bio}</p>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                </div>
                                <div className="text-right text-sm text-gray-500">
                                    <p>{formatDate(displayArticle?.published_at)}</p>
                                    <p>{displayArticle?.reading_time || 5} min baca</p>
                                </div>
                            </div>

                            {/* Tags */}
                            {displayArticle?.tags && (
                                <div className="flex flex-wrap gap-2 mb-6">
                                    {displayArticle.tags.split(',').map((tag, index) => (
                                        <span key={index} className="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm">
                                            #{tag.trim()}
                                        </span>
                                    ))}
                                </div>
                            )}

                            {/* Main Content */}
                            <div 
                                className="prose prose-lg max-w-none prose-headings:text-gray-800 prose-p:text-gray-600 prose-a:text-green-600"
                                dangerouslySetInnerHTML={{ __html: displayArticle?.content }}
                            />

                            {/* View Count */}
                            <div className="mt-8 pt-4 border-t border-gray-200 text-center text-gray-500">
                                <p>Artikel ini telah dibaca {displayArticle?.view_count || 0} kali</p>
                            </div>
                        </article>

                        {/* Back Button */}
                        <div className="text-center mb-8">
                            <Link 
                                href="/artikel" 
                                className="inline-flex items-center text-green-600 hover:text-green-700 font-semibold"
                            >
                                <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Daftar Artikel
                            </Link>
                        </div>

                        {/* Related Articles */}
                        {relatedArticles && relatedArticles.length > 0 && (
                            <div className="mt-12">
                                <h2 className="text-2xl font-bold text-gray-800 mb-6">Artikel Terkait</h2>
                                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    {relatedArticles.map((related) => (
                                        <Link 
                                            key={related.id} 
                                            href={`/artikel/${related.id}`}
                                            className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition"
                                        >
                                            <div className="h-40 bg-gray-200 relative">
                                                <img 
                                                    src={related.image || 'https://via.placeholder.com/400x300?text=Artikel'} 
                                                    alt={related.title}
                                                    className="w-full h-full object-cover"
                                                />
                                                <span className="absolute top-2 right-2 bg-green-600 text-white px-2 py-1 rounded-full text-xs">
                                                    {related.category}
                                                </span>
                                            </div>
                                            <div className="p-4">
                                                <h3 className="font-bold text-gray-800 line-clamp-2">{related.title}</h3>
                                                <p className="text-sm text-gray-500 mt-2">
                                                    {formatDate(related.published_at)}
                                                </p>
                                            </div>
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </Layout>
    );
}

