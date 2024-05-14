from PyPDF2 import PdfReader
from langchain.embeddings.openai import OpenAIEmbeddings
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.vectorstores import FAISS
from langchain.chains.question_answering import load_qa_chain
from langchain.llms import OpenAI
import sys
import os
from dotenv import load_dotenv


def get_response(user_input, file):
    load_dotenv()
    api_key = os.getenv('OPENAI_API_KEY')
    
    raw_text=''
    if file.endswith(".pdf"):
        reader = PdfReader(file)
        raw_text = ''
        for i, page in enumerate(reader.pages):
            text = page.extract_text()
            if text:
                raw_text += text
    
    elif file.endswith(".txt"):
        encodings = ['utf-8', 'latin1', 'utf-16']
        for encoding in encodings:
            try:
                with open(file, 'r', encoding=encoding) as f:
                    raw_text = f.read()
                break
            except UnicodeDecodeError:
                pass

    if "explain" in user_input.lower() or "elaborate" in user_input.lower() or "describe" in user_input.lower() or "detail" in user_input.lower() or "expand" in user_input.lower() or "elucidate" in user_input.lower() or "interpret" in user_input.lower() or "detailed" in user_input.lower() :
        
        text_splitter = RecursiveCharacterTextSplitter(
   
    chunk_size=3500, 
    chunk_overlap=0,
    length_function=len,
)
        texts = text_splitter.split_text(raw_text)
        embeddings = OpenAIEmbeddings(openai_api_key=api_key, model="text-embedding-ada-002")
 
        docsearch = FAISS.from_texts(texts, embeddings)
        chain = load_qa_chain(OpenAI(temperature=0.5,model='gpt-3.5-turbo-instruct',max_tokens = 1024), chain_type="refine")
        query = "Based on the document, " + user_input + ". Provide a comprehensive and informative answer, organized in multiple, well-structured paragraphs."
    
    else:
        text_splitter = RecursiveCharacterTextSplitter(

    chunk_size=2000, 
    chunk_overlap=0,
    length_function=len,
)
        texts = text_splitter.split_text(raw_text)
        embeddings = OpenAIEmbeddings(openai_api_key=api_key, model="text-embedding-ada-002")
     
        docsearch = FAISS.from_texts(texts, embeddings)
        chain = load_qa_chain(OpenAI(temperature=0.1,model='gpt-3.5-turbo-instruct'), chain_type="stuff")
        query = "Based on the document, " + user_input
    docs = docsearch.similarity_search(query)   
    result = chain.run(input_documents=docs, question=query)
    return result

if __name__ == "__main__":
    if len(sys.argv) == 3:
        user_input = sys.argv[1]
        file = sys.argv[2]
        result = get_response(user_input, file)
        print(result)

                                                                                                        
