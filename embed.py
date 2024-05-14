from langchain.embeddings.openai import OpenAIEmbeddings
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.vectorstores import FAISS
from langchain.chains.question_answering import load_qa_chain
from langchain.llms import OpenAI
import os

from dotenv import load_dotenv
import fitz

def get_embeddings(file):
    load_dotenv()
    api_key = os.getenv('OPENAI_API_KEY')
    if file.endswith(".pdf"):
        doc = fitz.open(file)
        raw_text = ""
        for page_number in range(doc.page_count):
            page = doc[page_number]
            raw_text += page.get_text()
        doc.close()

    elif file.endswith(".txt"):
        encodings = ['utf-8', 'latin1', 'utf-16']
        for encoding in encodings:
            try:
                with open(file, 'r', encoding=encoding) as f:
                    raw_text = f.read()
                break
            except UnicodeDecodeError:
                pass
        
    text_splitter = RecursiveCharacterTextSplitter(
    chunk_size=3500, 
    chunk_overlap=100,
    length_function=len,
)
    texts = text_splitter.split_text(raw_text)
    embeddings = OpenAIEmbeddings(openai_api_key=api_key, model="text-embedding-ada-002")
    docsearch = FAISS.from_texts(texts, embeddings)
    chain = load_qa_chain(OpenAI(temperature=0.5,model='gpt-3.5-turbo-instruct',max_tokens = 1024), chain_type="refine")
    return docsearch, chain
print(get_embeddings("uploads/9-23.pdf"))